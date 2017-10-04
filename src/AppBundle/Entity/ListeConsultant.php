<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * ListeConsultant
 *
 * @ORM\Table(name="liste_consultant", indexes={@ORM\Index(name="liste_consultant_besoin", columns={"besoin_besoin_id"}), @ORM\Index(name="liste_consultant_user", columns={"user_user_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ListeConsultantRepository")
 */
class ListeConsultant
{
    /**
     * @var integer
     *
     * @ORM\Column(name="liste_consultant_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @Serializer\Groups({"ApiListeConsultantGroup"})
     */
    private $id;

    /**
     * @var Besoin
     *
     * @ORM\ManyToOne(targetEntity="Besoin")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="besoin_besoin_id", referencedColumnName="besoin_id")
     * })
     *
     * @Serializer\Groups({"ApiListeConsultantGroup"})
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
     * @Serializer\Groups({"ApiListeConsultantGroup"})
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
     * Set besoin
     *
     * @param \AppBundle\Entity\Besoin $besoin
     *
     * @return ListeConsultant
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
     * @return ListeConsultant
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
