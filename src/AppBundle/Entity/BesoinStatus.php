<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * BesoinStatus
 *
 * @ORM\Table(name="besoin_status")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BesoinStatusRepository")
 */
class BesoinStatus
{
    /**
     * @var integer
     *
     * @ORM\Column(name="besoin_status_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @Serializer\Groups({"ApiBesoinStatusGroup"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="besoin_status_label", type="string", length=255, nullable=false)
     *
     * @Serializer\Groups({"ApiBesoinStatusGroup"})
     */
    private $label;



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
     * Set label
     *
     * @param string $label
     *
     * @return BesoinStatus
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }
}
