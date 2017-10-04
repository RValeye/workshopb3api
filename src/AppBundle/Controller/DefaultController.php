<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Besoin;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends FOSRestController
{
    /**
     * @Rest\View()
     * @Rest\Get("/")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $besoins = $em->getRepository('AppBundle:Besoin')->findBy([
            'active' => true
        ]);

        return $besoins;
    }
}
