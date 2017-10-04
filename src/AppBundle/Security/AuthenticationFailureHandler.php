<?php
/**
 * Created by PhpStorm.
 * User: Romain
 * Date: 22/08/2017
 * Time: 11:40
 */

namespace AppBundle\Security;


use function Couchbase\defaultDecoder;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class AuthenticationFailureHandler implements AuthenticationFailureHandlerInterface
{
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse(['code' => 401, 'message' => $exception->getMessage()], 401);
    }
}