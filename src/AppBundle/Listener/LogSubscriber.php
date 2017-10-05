<?php
/**
 * Created by PhpStorm.
 * User: Romain
 * Date: 01/08/2017
 * Time: 22:42
 */

namespace AppBundle\Listener;


use AppBundle\Entity\BesoinModification;
use AppBundle\Entity\BesoinStatusModified;
use AppBundle\Entity\Log;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LogSubscriber implements EventSubscriber
{
    protected $request;
    protected $container;
    protected $storage;
    protected $logs;

    public static $update = 1;
    public static $insert = 1;
    public static $remove = 1;

    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::preUpdate,
            Events::preRemove,
            Events::postFlush,
        ];
    }

    public function __construct(RequestStack $request, ContainerInterface $container, TokenStorageInterface $storage)
    {
        $this->request = $request->getCurrentRequest();
        $this->container = $container;
        $this->storage = $storage;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $user = $this->storage->getToken()->getUser();
        $this->logs = [];
        $entity = $args->getEntity();
        $route = $this->request->attributes->get('_route');
        $em = $args->getEntityManager();
        $meta = $em->getClassMetadata(get_class($entity));

        // Pas de log pour les logs, et les tables de liaisons sont gérées séparément
        if (!$entity instanceof Log) {
            if (!$entity instanceof BesoinModification
                && !$entity instanceof BesoinStatusModified
            ) {
                $id = '';
                if ($meta->getIdentifierValues($entity)) {
                    foreach ($meta->getIdentifierValues($entity) as $identifier) {
                        $id = $identifier;
                    }
                }

                $log = new Log();
                $log->setUser($user)
                    ->setDesc('Création')
                    ->setLinkedTable($meta->getTableName())
                    ->setLinkedId($id)
                    ->setOrigin($route);

            } elseif ($entity instanceof BesoinModification) {
                $groupId = $entity->getGroup()->getId();

                $log = new Log();
                $log->setUser($user)
                    ->setDesc('Un besoin a été modifié')
                    ->setLinkedTable($em->getClassMetadata('AppBundle:BesoinModification')->getTableName())
                    ->setLinkedField('besoin_modification_id')
                    ->setLinkedId($groupId)
                    ->setOrigin($route);

            } else { // ($entity instanceof BesoinStatusModified)
                $projectId = $entity->getProject()->getId();

                $log = new Log();
                $log->setUser($user)
                    ->setDesc('Un utilisateur se lie à un projet')
                    ->setLinkedTable($em->getClassMetadata('AppBundle:ProjectOwner')->getTableName())
                    ->setLinkedField('project_id')
                    ->setLinkedId($projectId)
                    ->setOrigin($route);

            }
            $this->logs[] = $log;
            self::$insert = 0;
        }
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $this->logs = [];
        $user = $this->storage->getToken()->getUser();
        $route = $this->request->attributes->get('_route');
        $em = $args->getEntityManager();
        $entity = $args->getEntity();
        $meta = $em->getClassMetadata(get_class($entity));
        $oldValue = '';
        $newValue = '';
        $id = '';
        if ($meta->getIdentifierValues($entity)) {
            foreach ($meta->getIdentifierValues($entity) as $identifier) {
                $id = $identifier;
            }
        }

        foreach ($args->getEntityChangeSet() as $key => $value) {
            if ($value[0] instanceof \DateTime) {
                $oldValue = $value[0]->format('Y-m-d H:i:s');
                $newValue = $args->getNewValue($key)->format('Y-m-d H:i:s');

            } elseif (is_object($value[0])) {
                $objectOld = $args->getOldValue($key);
                $objectNew = $args->getNewValue($key);

                $metaObj = $em->getClassMetadata(get_class($objectOld));
                $idsObjOld = $metaObj->getIdentifierValues($objectOld);
                foreach ($idsObjOld as $idOld) {
                    $oldValue = $idOld;
                }

                if (is_object($objectNew)) {
                    $metaObj = $em->getClassMetadata(get_class($objectNew));
                    $idsObjNew = $metaObj->getIdentifierValues($objectNew);
                    foreach ($idsObjNew as $idNew) {
                        $newValue = $idNew;
                    }
                }

            } else {
                $oldValue = $args->getOldValue($key);
                $newValue = $args->getNewValue($key);

            }

            $log = new Log();
            $log->setUser($user)
                ->setDesc('Modification')
                ->setLinkedTable($meta->getTableName())
                ->setLinkedField($meta->getColumnName($key))
                ->setLinkedId($id)
                ->setOrigin($route)
                ->setOldValue($oldValue)
                ->setNewValue($newValue);

            $this->logs[] = $log;
        }
        self::$update = 0;
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $this->logs = [];
        $user = $this->storage->getToken()->getUser();
        $route = $this->request->attributes->get('_route');
        $em = $args->getEntityManager();
        $entity = $args->getEntity();
        $meta = $em->getClassMetadata(get_class($entity));
        $id = '';
        if ($meta->getIdentifierValues($entity)) {
            foreach ($meta->getIdentifierValues($entity) as $identifier) {
                $id = $identifier;
            }
        }

        $log = new Log();
        $log->setUser($user)
            ->setDesc('Suppression')
            ->setLinkedTable($meta->getTableName())
            ->setLinkedId($id)
            ->setOrigin($route);

        $this->logs[] = $log;

    }

    public function postFlush(PostFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        $updatedEntities = $uow->getScheduledEntityUpdates();
        $flushedEntities = $uow->getScheduledEntityInsertions();
        $removedEntities = $uow->getScheduledCollectionDeletions();

        if (!$updatedEntities && !$flushedEntities && !$removedEntities) {
            if (count($this->logs) > 0) {
                foreach ($this->logs as $log) {
                    $em->persist($log);
                }
                $em->flush();
            }
        }

        self::$remove = $removedEntities ? 1 : 0;
    }
}