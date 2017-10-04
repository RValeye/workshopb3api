<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use JMS\Serializer\Annotation\Groups;

/**
 * User
 *
 * @ORM\Table(name="user", indexes={@ORM\Index(name="user_type_user", columns={"type_user_type_user_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements AdvancedUserInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @Serializer\Groups({"ApiUserGroup", "ApiListeConsultantGroup"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="user_name", type="string", length=255, nullable=false)
     *
     * @Serializer\Groups({"ApiUserGroup", "ApiListeConsultantGroup"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="user_email", type="string", length=255, nullable=false)
     *
     * @Serializer\Groups({"ApiUserGroup", "ApiListeConsultantGroup"})
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="user_api_key", type="string", length=255, nullable=true)
     *
     * @Serializer\Groups({"ApiUserGroup", "ApiListeConsultantGroup"})
     */
    private $apikey;

    /**
     * @var TypeUser
     *
     * @ORM\ManyToOne(targetEntity="TypeUser")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_user_type_user_id", referencedColumnName="type_user_id")
     * })
     * @Serializer\Groups({"ApiUserGroup"})
     */
    private $typeUser;



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
     * Set name
     *
     * @param string $name
     *
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set apikey
     *
     * @param string $apikey
     *
     * @return User
     */
    public function setApikey($apikey)
    {
        $this->apikey = $apikey;

        return $this;
    }

    /**
     * Get apikey
     *
     * @return string
     */
    public function getApikey()
    {
        return $this->apikey;
    }

    /**
     * Set typeUser
     *
     * @param \AppBundle\Entity\TypeUser $typeUser
     *
     * @return User
     */
    public function setTypeUser(\AppBundle\Entity\TypeUser $typeUser = null)
    {
        $this->typeUser = $typeUser;

        return $this;
    }

    /**
     * Get typeUser
     *
     * @return \AppBundle\Entity\TypeUser
     */
    public function getTypeUser()
    {
        return $this->typeUser;
    }

    /**
     * Checks whether the user's account has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw an AccountExpiredException and prevent login.
     *
     * @return bool true if the user's account is non expired, false otherwise
     *
     * @see AccountExpiredException
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * Checks whether the user is locked.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a LockedException and prevent login.
     *
     * @return bool true if the user is not locked, false otherwise
     *
     * @see LockedException
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * Checks whether the user's credentials (password) has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a CredentialsExpiredException and prevent login.
     *
     * @return bool true if the user's credentials are non expired, false otherwise
     *
     * @see CredentialsExpiredException
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * Checks whether the user is enabled.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a DisabledException and prevent login.
     *
     * @return bool true if the user is enabled, false otherwise
     *
     * @see DisabledException
     */
    public function isEnabled()
    {
        return true;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return 'gfi';
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
    }
}
