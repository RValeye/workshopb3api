<?php
/**
 * Created by PhpStorm.
 * User: Romain
 * Date: 04/08/2017
 * Time: 09:19
 */

namespace AppBundle\Repository;


use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class BaseRepository extends EntityRepository
{
    protected $_alias;

    public function __construct($entityManager, $classMetadata, $alias)
    {
        parent::__construct($entityManager, $classMetadata);
        $this->_alias = $alias;
    }

    public function getCount($filters)
    {
        $q = $this->createQueryBuilder($this->_alias)
            ->select("COUNT({$this->_alias})");

        $this->addFilters($q, $filters);

        return $q->getQuery()->getSingleResult();
    }

    public function getAll($filters)
    {
        $q = $this->createQueryBuilder($this->_alias)
            ->select($this->_alias);
        $this->addFilters($q, $filters);
        return $q->getQuery()->getResult(Query::HYDRATE_OBJECT);
    }

    public function filter($start, $nb, $filters)
    {
        //$i=1;
        //$totalfilters = count($filters);
        //$filtreString = '';
        //$flagOrderBy = false;
        $q = $this->createQueryBuilder($this->_alias)
            ->select($this->_alias);

        if (isset($filters['q'])) {
            $queryFilter = $filters['q'];
            unset($filters['q']);
            $this->elaboratedFilter($q, $queryFilter);
        }
        $this->addFilters($q, $filters);

        $q->setMaxResults($nb)
            ->setFirstResult($start);

        return $q->getQuery()->getResult(Query::HYDRATE_OBJECT);
    }

    protected function addFilters(QueryBuilder &$q, $filters)
    {
        $refl = new \ReflectionClass(new $this->_entityName());
        $docReader = new AnnotationReader();

        $deepOrder = false;
        foreach($filters as $filterkey => $filtervalue){

            if(($filterkey == 'order' || $filterkey == 'orderBy')){
                /*if(!$flagOrderBy){
                    $q->orderBy('f.'.$filters['orderBy'],$filters['order']);
                    $flagOrderBy = true;
                }*/

                // ?orderBy=groupe+DESC+fonction+ASC+abrev
                // ==
                // ?orderBy=groupe DESC fonction ASC abrev
                $orders = explode(' ', $filtervalue);
                for ($i = 0; $i < count($orders); $i += 2) {
                    $sort = $orders[$i];
                    $order = isset($orders[$i+1]) ? $orders[$i+1] : 'ASC'; // si impair, dernier order ascendant

                    $q->addOrderBy("{$this->_alias}.$sort", $order);
                }
                unset($i, $sort, $order, $orders);

            } elseif ($filterkey == 'deep' || $filterkey == 'deepEntity' || $filterkey == 'deepOrder') {

                if (!$deepOrder) { // do deepOrder

                    $deepAlias = substr($filters['deepEntity'], 0, 1) . '0'; // 0 pour éviter les conflits d'alias avec l'entité principale si même alias
                    $q->join("{$this->_alias}.{$filters['deepEntity']}", $deepAlias);

                    $orders = explode(' ', $filters['deepOrder']);
                    for ($i = 0; $i < count($orders); $i += 2) {
                        $sort = $orders[$i];
                        $order = isset($orders[$i+1]) ? $orders[$i+1] : null; // si impair, alors dernier order = null = 'ASC' plus tard dans doctrine

                        $q->addOrderBy("$deepAlias.$sort", $order);
                    }

                    $deepOrder = true; // don't redo it
                }
            } else {
                try {
                    $docInfos = $docReader->getPropertyAnnotations($refl->getProperty($filterkey));
                } catch (\Exception $e) {
                    $docInfos = array();
                }

                $docInfos = array_values(array_filter($docInfos, function ($annotation) {
                    return $annotation instanceof Column;
                }));

                if (!empty($docInfos) && $column = $docInfos[0]) {
                    if ($column->type === 'string') {
                        $q->andWhere("{$this->_alias}.$filterkey LIKE :$filterkey");

                        $q->setParameter($filterkey,"%{$filtervalue}%");
                    } else {
                        $q->andWhere("{$this->_alias}.$filterkey = '$filtervalue'");
                    }
                } else {
                    $q->andHaving("$filterkey = '$filtervalue'");
                }
            }
        }
    }

    protected function elaboratedFilter(QueryBuilder &$q, $filter)
    {
        $arr = json_decode($filter, true);

        foreach ($arr as $key => $value) {
            if (!is_array($value)) {
                $q->andWhere("{$this->_alias}.$key = '$value'");
            } else {
                $q->andWhere($this->convertURLOperators($q, $key, $key, $value));
            }
        }
    }

    /**
     * Conversion des opérateurs dans la paire clé/valeur en paramètre
     * Cette fonction se rappelle elle-même si elle trouve un opérateur en clé (que la valeur est en tableau en fait)
     *
     * @param QueryBuilder $q
     * @param string $key
     * @param string $lastKey
     * @param mixed|array $value
     * @return Query\Expr\Comparison|mixed|null
     */
    protected function convertURLOperators(QueryBuilder &$q, $key, $lastKey, $value) {
        $andOr = [
            '$or' => 'orX',
            '$and' => 'andX'
        ];

        $logic = [
            '$not'      => 'not',
            '$in'       => 'in',
            '$nin'      => 'notIn',
            '$regex'    => 'like',
            '$gt'       => 'gt',
            '$gte'      => 'gte',
            '$lt'       => 'lt',
            '$lte'      => 'lte',
            '$bt'       => 'between',
        ];

        // si l'opérateur est un and ou un or, on procède à un andX ou un orX grâce au QueryBuilder de Symfony
        if (array_key_exists($key, $andOr)) {
            $function = $andOr[$key]; // récup du nom de la fonction à appeler
            $elements = [];

            foreach ($value as $andOrX) { // pour chaque élément du tableau qui composera l'ensemble X=1 AND/OR Y=2 AND/OR Z=3

                if (is_array($andOrX)) {
                    foreach ($andOrX as $fieldName => $fieldValue) {
                        if (is_array($fieldValue)) {
                            foreach ($fieldValue as $cond => $v) {
                                $elements[] = $this->convertURLOperators($q, $cond, $fieldName, $v);
                            }
                        } else { // si fieldValue n'est pas un tableau, on suggère qu'il n'y a pas d'opérateur, et donc simple égalité
                            $elements[] = $q->expr()->eq("{$this->_alias}.$fieldName", "'$fieldValue'");
                        }
                    }
                } else { // si un élément du AND/OR n'est pas un tableau, on suggère qu'il n'y a pas d'opérateur, et donc simple égalité
                    $elements[] = $q->expr()->eq("{$this->_alias}.$key", "'$andOrX'");
                }
            }

            // On appelle la fonction concernée des expressions de QueryBuilder
            // On ne peut pas appeler la fonction directement car le nombre d'éléments est inconnu --> call_user_func_array
            return call_user_func_array(array($q->expr(), $function), $elements);

        } elseif (array_key_exists($key, $logic)) {
            $function = $logic[$key]; // récup du nom de la fonction d'opérateur "simple" (tout sauf andX et orX)

            // Utilisation du $lastKey au lieu de $key puisque $key est un opérateur logique
            // cf. foreach lignes 179-187

            if ($function == 'like') { // cas particulier like, mettre des %
                return $q->expr()->$function("{$this->_alias}.$lastKey", "'%$value%'");
            } elseif ($function == 'between') { // cas particulier between, value doit etre un tableau de deux valeurs
                list ($x, $y) = $value;
                return $q->expr()->$function("{$this->_alias}.$lastKey", $x, $y);
            } else {
                if (is_array($value)) { // fonctions in et notIn
                    return $q->expr()->$function("{$this->_alias}.$lastKey", $value);
                } else { // reste
                    return $q->expr()->$function("{$this->_alias}.$lastKey", "'$value'");
                }
            }
        } else {
            if (is_array($value) && !empty($value)) {
                foreach ($value as $k=>$item) {
                    $function = $logic[$k];
                    return $q->expr()->$function("{$this->_alias}.$key", $item);
                }
                return null; //should never go there
            } else {
                return $q->expr()->eq("{$this->_alias}.$key", "'$value'");
            }
        }
    }
}