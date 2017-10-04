<?php
/**
 * Created by PhpStorm.
 * User: Romain
 * Date: 22/08/2017
 * Time: 11:33
 */

namespace AppBundle\Security;


use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use FOS\RestBundle\View\View;
use JMS\Serializer\ContextFactory\SerializationContextFactoryInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private $dispatcher;
    private $serializer;
    private $serializationContext;
    private $em;

    public function __construct(Serializer $serializer, EventDispatcherInterface $dispatcher, EntityManager $entityManager)
    {
        $this->serializer = $serializer;
        $this->dispatcher = $dispatcher;
        $this->em = $entityManager;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $serializationContext = SerializationContext::create()->enableMaxDepthChecks()->setSerializeNull(true);
        /** @var User $user */
        $user = $token->getUser();

        if (isset($errorData)) {
            return new JsonResponse($errorData, $errorData['code']);
        }

        $serializedToken = $this->serializer->serialize($user, 'json', $serializationContext->setGroups(['Default', 'ApiUserGroup']));

        $response = new Response($serializedToken, Response::HTTP_OK);

        return $response;
    }

}