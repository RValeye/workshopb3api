<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ListeKeySuccess
 *
 * @ORM\Table(name="liste_key_success", indexes={@ORM\Index(name="liste_key_success_besoin", columns={"besoin_besoin_id"}), @ORM\Index(name="liste_key_success_key_success", columns={"key_success_key_success_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ListeKeySuccessRepository")
 */
class ListeKeySuccess
{
    /**
     * @var integer
     *
     * @ORM\Column(name="liste_key_success_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Besoin
     *
     * @ORM\ManyToOne(targetEntity="Besoin")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="besoin_besoin_id", referencedColumnName="besoin_id")
     * })
     */
    private $besoin;

    /**
     * @var KeySuccess
     *
     * @ORM\ManyToOne(targetEntity="KeySuccess")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="key_success_key_success_id", referencedColumnName="key_success_id")
     * })
     */
    private $keySuccess;



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
     * @return ListeKeySuccess
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
     * Set keySuccess
     *
     * @param \AppBundle\Entity\KeySuccess $keySuccess
     *
     * @return ListeKeySuccess
     */
    public function setKeySuccess(\AppBundle\Entity\KeySuccess $keySuccess = null)
    {
        $this->keySuccess = $keySuccess;

        return $this;
    }

    /**
     * Get keySuccess
     *
     * @return \AppBundle\Entity\KeySuccess
     */
    public function getKeySuccess()
    {
        return $this->keySuccess;
    }
}
