<?php
/**
 * Created by PhpStorm.
 * User: Romain
 * Date: 08/08/2017
 * Time: 12:16
 */

namespace AppBundle\Security;


use AppBundle\Security\AuthTokenExtractor;
use AppBundle\Security\WebServiceUserProvider;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Guard\GuardAuthenticatorInterface;
use Symfony\Component\Security\Guard\Token\GuardTokenInterface;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;
use Symfony\Component\Security\Guard\Token\PreAuthenticationGuardToken;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;

class ApiKeyAuthenticator implements GuardAuthenticatorInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var TokenStorageInterface
     */
    private $preAuthenticationTokenStorage;

    /**
     * @var AuthTokenExtractor
     */
    private $tokenExtractor;

    /**
     * ApiKeyAuthenticator constructor.
     * @param EventDispatcherInterface $dispatcher
     * @param AuthTokenExtractor $extractor
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        AuthTokenExtractor $extractor
    )
    {
        $this->dispatcher = $dispatcher;
        $this->tokenExtractor = $extractor;
        $this->preAuthenticationTokenStorage = new TokenStorage();
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new JsonResponse(['code' => 417, 'message' => 'Token or Apikey not found in request.'], 417);
    }

    public function getCredentials(Request $request)
    {
        if (false === ($key = $this->tokenExtractor->extract($request))) {
            return;
        }

        return $key;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (!$userProvider instanceof WebServiceUserProvider) {
            throw new \InvalidArgumentException(
                sprintf('Parameter $userProvider expected to be WebServiceUserProvider, got %s', get_class($userProvider))
            );
        }

        $cleEntity = $userProvider->loadUserByApiKey($credentials);

        return $cleEntity;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $code = $exception->getCode() === 0 ? 401 : $exception->getCode();
        return new JsonResponse(['code' => $code, 'message' => $exception->getMessage()], $code);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $request->getSession()->set('user', $token->getUser());
        $request->getSession()->save();
        return;
    }

    public function supportsRememberMe()
    {
        return false;
    }

    public function createAuthenticatedToken(UserInterface $user, $providerKey)
    {
        return new PostAuthenticationGuardToken(
            $user,
            $providerKey,
            ['ROLE_USER']
        );
    }


}