<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * BesoinStatusModified
 *
 * @ORM\Table(name="besoin_status_modified", indexes={@ORM\Index(name="besoin_status_modified_besoin", columns={"besoin_besoin_id"}), @ORM\Index(name="besoin_status_modified_besoin_status", columns={"besoin_status_besoin_status_id"}), @ORM\Index(name="besoin_status_modified_user", columns={"user_user_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BesoinStatusModifiedRepository")
 */
class BesoinStatusModified
{
    /**
     * @var integer
     *
     * @ORM\Column(name="status_modified_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @Serializer\Groups({"ApiBesoinStatusModifiedGroup"})
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="status_modified_date", type="datetime", nullable=false)
     *
     * @Serializer\Groups({"ApiBesoinStatusModifiedGroup"})
     */
    private $date;

    /**
     * @var Besoin
     *
     * @ORM\ManyToOne(targetEntity="Besoin")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="besoin_besoin_id", referencedColumnName="besoin_id")
     * })
     *
     * @Serializer\Groups({"ApiBesoinStatusModifiedGroup"})
     */
    private $besoin;

    /**
     * @var BesoinStatus
     *
     * @ORM\ManyToOne(targetEntity="BesoinStatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="besoin_status_besoin_status_id", referencedColumnName="besoin_status_id")
     * })
     *
     * @Serializer\Groups({"ApiBesoinStatusModifiedGroup"})
     */
    private $besoinStatus;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_user_id", referencedColumnName="user_id")
     * })
     *
     * @Serializer\Groups({"ApiBesoinStatusModifiedGroup"})
     */
    private $user;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return BesoinStatusModified
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set besoin
     *
     * @param \AppBundle\Entity\Besoin $besoin
     *
     * @return BesoinStatusModified
     */
    public function setBesoin(\AppBundle\Entity\Besoin $besoin = null)
    {
        $this->besoin = $besoin;

        return $this;
    }

    /**
     * Get besoin
     *
     * @return \AppBundle\Entity\Besoin
     */
    public function getBesoin()
    {
        return $this->besoin;
    }

    /**
     * Set besoinStatus
     *
     * @param \AppBundle\Entity\BesoinStatus $besoinStatus
     *
     * @return BesoinStatusModified
     */
    public function setBesoinStatus(\AppBundle\Entity\BesoinStatus $besoinStatus = null)
    {
        $this->besoinStatus = $besoinStatus;

        return $this;
    }

    /**
     * Get besoinStatus
     *
     * @return \AppBundle\Entity\BesoinStatus
     */
    public function getBesoinStatus()
    {
        return $this->besoinStatus;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return BesoinStatusModified
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
