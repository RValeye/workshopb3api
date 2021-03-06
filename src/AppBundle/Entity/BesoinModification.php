<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * BesoinModification
 *
 * @ORM\Table(name="besoin_modification", indexes={@ORM\Index(name="besoin_modification_besoin", columns={"besoin_besoin_id"}), @ORM\Index(name="besoin_modification_user", columns={"user_user_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BesoinModificationRepository")
 */
class BesoinModification
{
    /**
     * @var integer
     *
     * @ORM\Column(name="besoin_modification_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @Serializer\Groups({"ApiBesoinModificationGroup"})
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="besoin_modification_date", type="datetime", nullable=false)
     *
     * @Serializer\Groups({"ApiBesoinModificationGroup"})
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
     * @Serializer\Groups({"ApiBesoinModificationGroup"})
     */
    private $besoin;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_user_id", referencedColumnName="user_id")
     * })
     *
     * @Serializer\Groups({"ApiBesoinModificationGroup"})
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
     * @return BesoinModification
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
     * @return BesoinModification
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
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return BesoinModification
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
