<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * KeySuccess
 *
 * @ORM\Table(name="key_success")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\KeySuccessRepository")
 */
class KeySuccess
{
    /**
     * @var integer
     *
     * @ORM\Column(name="key_success_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="key_success_label", type="string", length=255, nullable=false)
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
     * @return KeySuccessRepository
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
