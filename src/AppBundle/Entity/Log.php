<?php
/**
 * Created by PhpStorm.
 * User: Romain
 * Date: 01/08/2017
 * Time: 22:29
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Log
 * @ORM\Table(name="log")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LogRepository")
 */
class Log
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="log_id", type="integer")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="log_date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="log_desc", type="text")
     */
    private $desc;

    /**
     * @var string
     *
     * @ORM\Column(name="log_linked_table", type="string", length=20, nullable=true)
     */
    private $linkedTable;

    /**
     * @var string
     *
     * @ORM\Column(name="log_linked_field", type="string", length=50, nullable=true)
     */
    private $linkedField;

    /**
     * @var integer
     *
     * @ORM\Column(name="log_linked_id", type="integer", nullable=true)
     */
    private $linkedId;

    /**
     * @var string
     *
     * @ORM\Column(name="log_old_value", type="text", nullable=true)
     */
    private $oldValue;

    /**
     * @var string
     *
     * @ORM\Column(name="log_new_value", type="text", nullable=true)
     */
    private $newValue;

    /**
     * @var string
     *
     * @ORM\Column(name="log_origin", type="string", length=255, nullable=true)
     */
    private $origin;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="logs")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     */
    private $user;

    public function __construct()
    {
        $this->date = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return Log
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return string
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * @param string $desc
     * @return Log
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;
        return $this;
    }

    /**
     * @return string
     */
    public function getLinkedTable()
    {
        return $this->linkedTable;
    }

    /**
     * @param string $linkedTable
     * @return Log
     */
    public function setLinkedTable($linkedTable)
    {
        $this->linkedTable = $linkedTable;
        return $this;
    }

    /**
     * @return string
     */
    public function getLinkedField()
    {
        return $this->linkedField;
    }

    /**
     * @param string $linkedField
     * @return Log
     */
    public function setLinkedField($linkedField)
    {
        $this->linkedField = $linkedField;
        return $this;
    }

    /**
     * @return int
     */
    public function getLinkedId()
    {
        return $this->linkedId;
    }

    /**
     * @param int $linkedId
     * @return Log
     */
    public function setLinkedId($linkedId)
    {
        $this->linkedId = $linkedId;
        return $this;
    }

    /**
     * @return string
     */
    public function getOldValue()
    {
        return $this->oldValue;
    }

    /**
     * @param string $oldValue
     * @return Log
     */
    public function setOldValue($oldValue)
    {
        $this->oldValue = $oldValue;
        return $this;
    }

    /**
     * @return string
     */
    public function getNewValue()
    {
        return $this->newValue;
    }

    /**
     * @param string $newValue
     * @return Log
     */
    public function setNewValue($newValue)
    {
        $this->newValue = $newValue;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @param string $origin
     * @return Log
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Log
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }
}