<?php
/**
 * Created by PhpStorm.
 * User: Romain
 * Date: 23/08/2017
 * Time: 11:36
 */

namespace AppBundle\Security;


use Symfony\Component\HttpFoundation\Request;

class AuthTokenExtractor
{
    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var string
     */
    protected $name;

    public function __construct($prefix, $name)
    {
        $this->prefix = $prefix;
        $this->name = $name;
    }

    public function extract(Request $request)
    {
        if (!$request->headers->has($this->name)) {
            return false;
        }

        $authHeader = $request->headers->get($this->name);

        $headerParts = explode(' ', $authHeader);

        if (count($headerParts) === 1 && empty($this->prefix)) {
            return $authHeader;
        } elseif (count($headerParts) === 2 && $headerParts[0] === $this->prefix) {
            return $headerParts[1];
        } else {
            return false;
        }
    }
}