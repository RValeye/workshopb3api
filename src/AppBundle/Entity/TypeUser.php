<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * TypeUser
 *
 * @ORM\Table(name="type_user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TypeUserRepository")
 */
class TypeUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="type_user_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Serializer\Groups({"ApiUserGroup", "ApiTypeUserGroup"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type_user_label", type="string", length=255, nullable=false)
     *
     * @Serializer\Groups({"ApiUserGroup", "ApiTypeUserGroup"})
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
     * @return TypeUserRepository
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
