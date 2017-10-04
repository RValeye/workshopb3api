<?php
/**
 * Created by PhpStorm.
 * User: Loïs
 * Date: 30/05/2017
 * Time: 11:55
 */

namespace AppBundle\Security;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class WebServiceUserProvider implements UserProviderInterface
{

    private $em;
    private $container;

    public function __construct(EntityManager $em, ContainerInterface $container){
        $this->em = $em;
        $this->container = $container;
    }

    public function loadUserByApiKey($apikey)
    {
        //$userData = $this->em->getRepository('AppBundle:User')->findOneBy(['apikey' => $apikey]);
        $cle = $this->em->getRepository('AppBundle:User')->findOneBy(['apikey' => $apikey]);

        if (!$cle) {
            throw new UsernameNotFoundException(sprintf('Apikey %s not found', $apikey));
        }

        return $cle;
    }

    public function loadUserByUsername($username)
    {
        $user = $this->em->getRepository('AppBundle:User')->findOneBy(array('email' => $username));

        if ($user) {
            $session = $this->container->get('session');

            if (null === $session->get('user')) {
                $session->set('user', $user);

            }
            return $user;
        }

        throw new UsernameNotFoundException(
            sprintf('Username "%s" does not exist.', $username)
        );
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return User::class === $class;
    }
}