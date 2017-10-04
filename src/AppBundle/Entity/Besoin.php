<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Besoin
 *
 * @ORM\Table(name="besoin", indexes={@ORM\Index(name="besoin_client", columns={"client_client_id"}), @ORM\Index(name="besoin_user", columns={"user_user_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BesoinRepository")
 */
class Besoin
{
    /**
     * @var integer
     *
     * @ORM\Column(name="besoin_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @Serializer\Groups({"ApiBesoinGroup", "ApiListeConsultantGroup"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="besoin_title", type="string", length=255, nullable=false)
     *
     * @Serializer\Groups({"ApiBesoinGroup"})
     */
    private $title;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="besoin_date_create", type="datetime", nullable=false)
     *
     * @Serializer\Groups({"ApiBesoinGroup"})
     */
    private $dateCreate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="besoin_start_at_latest", type="datetime", nullable=true)
     *
     * @Serializer\Groups({"ApiBesoinGroup"})
     */
    private $startAtLatest;

    /**
     * @var string
     *
     * @ORM\Column(name="besoin_description", type="text", length=65535, nullable=false)
     *
     * @Serializer\Groups({"ApiBesoinGroup"})
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="besoin_location", type="string", length=255, nullable=false)
     *
     * @Serializer\Groups({"ApiBesoinGroup"})
     */
    private $location;

    /**
     * @var float
     *
     * @ORM\Column(name="besoin_rate", type="decimal", precision=14, scale=2, nullable=false)
     *
     * @Serializer\Groups({"ApiBesoinGroup"})
     */
    private $rate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="besoin_active", type="boolean", nullable=false)
     *
     * @Serializer\Groups({"ApiBesoinGroup"})
     */
    private $active;

    /**
     * @var string
     *
     * @ORM\Column(name="besoin_fiche_url", type="text", length=65535, nullable=false)
     *
     * @Serializer\Groups({"ApiBesoinGroup"})
     */
    private $ficheUrl;

    /**
     * @var Contact
     *
     * @ORM\ManyToOne(targetEntity="Contact")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client_client_id", referencedColumnName="contact_id")
     * })
     */
    private $contact;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_user_id", referencedColumnName="user_id")
     * })
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="besoin_desc_url", type="text", nullable=false)
     *
     * @Serializer\Groups({"ApiBesoinGroup"})
     */
    private $descUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="besoin_key_success", type="string", length=255)
     *
     * @Serializer\Groups({"ApiBesoinGroup"})
     */
    private $keySuccess;

    /**
     * @var float
     *
     * @ORM\Column(name="besoin_duration", type="decimal", precision=8, scale=2, nullable=false)
     */
    private $duration;

    /**
     * @var float
     *
     * @ORM\Column(name="besoin_frequency", type="decimal", precision=8, scale=2, nullable=false)
     */
    private $frequency;

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
     * Set title
     *
     * @param string $title
     *
     * @return Besoin
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set dateCreate
     *
     * @param \DateTime $dateCreate
     *
     * @return Besoin
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    /**
     * Get dateCreate
     *
     * @return \DateTime
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * Set startAtLatest
     *
     * @param \DateTime $startAtLatest
     *
     * @return Besoin
     */
    public function setStartAtLatest($startAtLatest)
    {
        $this->startAtLatest = $startAtLatest;

        return $this;
    }

    /**
     * Get startAtLatest
     *
     * @return \DateTime
     */
    public function getStartAtLatest()
    {
        return $this->startAtLatest;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Besoin
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set location
     *
     * @param string $location
     *
     * @return Besoin
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set rate
     *
     * @param string $rate
     *
     * @return Besoin
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return string
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Besoin
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set ficheUrl
     *
     * @param string $ficheUrl
     *
     * @return Besoin
     */
    public function setFicheUrl($ficheUrl)
    {
        $this->ficheUrl = $ficheUrl;

        return $this;
    }

    /**
     * Get ficheUrl
     *
     * @return string
     */
    public function getFicheUrl()
    {
        return $this->ficheUrl;
    }

    /**
     * Set contact
     *
     * @param \AppBundle\Entity\Contact $contact
     *
     * @return Besoin
     */
    public function setContact(\AppBundle\Entity\Contact $contact = null)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return \AppBundle\Entity\Contact
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Besoin
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

    /**
     * @return string
     */
    public function getDescUrl()
    {
        return $this->descUrl;
    }

    /**
     * @param string $descUrl
     * @return Besoin
     */
    public function setDescUrl($descUrl)
    {
        $this->descUrl = $descUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getKeySuccess()
    {
        return $this->keySuccess;
    }

    /**
     * @param string $keySuccess
     * @return Besoin
     */
    public function setKeySuccess($keySuccess)
    {
        $this->keySuccess = $keySuccess;
        return $this;
    }

    /**
     * @return float
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param float $duration
     * @return Besoin
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @return float
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    /**
     * @param float $frequency
     * @return Besoin
     */
    public function setFrequency($frequency)
    {
        $this->frequency = $frequency;
        return $this;
    }
}
