<?php

namespace AppBundle\Controller;

use Doctrine\DBAL\DBALException;
use FOS\RestBundle\Controller\FOSRestController;
//TODO : mettre l'entity correspondante au controller
use AppBundle\Entity\TypeUser;
use AppBundle\Form\TypeUserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response; // Utilisation de la vue de FOSRestBundle

// TODO: find and replace
//TypeUser
//typeuser
//id

class TypeUserController extends FOSRestController
{

    /**
     * Récupère la liste des typeuser sur un interval donné selon les paramètres passés
     *
     * @Rest\View()
     * @Rest\Get("/typeuser/{start}/{nb}", requirements={"start" = "\d+", "nb" = "\d+"})
     */
    public function getTypeUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $params = $request->query->all();

        $typeuser = $em->getRepository('AppBundle:TypeUser')->filter($request->get('start'), $request->get('nb'), $params);

        return $this->view($typeuser, Response::HTTP_OK);
    }

    /**
     * Récupère le nombre de typeuser sur un interval donné selon les paramètres passés
     * @Rest\View()
     * @Rest\Get("/typeuser/count")
     */
    public function getTypeUserCountAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $params = $request->query->all();


        $typeuser = $em->getRepository('AppBundle:TypeUser')->getCount($params);


        return $this->view($typeuser, Response::HTTP_OK);
    }

    /**
     * Récupère un typeuser identifié par le paramètre 'id'
     *
     * @Rest\View()
     * @Rest\Get("/typeuser/{id}", requirements={"id" = "\d+"})
     */
    public function getOneTypeUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $typeuser = $em->getRepository('AppBundle:TypeUser')->find($request->get('id'));
        if($typeuser == null){
            return $this->view(false, Response::HTTP_NOT_FOUND);
        }
        return $this->view($typeuser, Response::HTTP_OK);
    }

    /**
     * Crée une typeuser
     *
     * @Rest\View()
     * @Rest\Post("/typeuser")
     */
    public function postTypeUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $typeuser = new TypeUser();

        $form = $this->createForm(TypeUserType::class, $typeuser);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        try{
            if($form->isValid()) {
                $em->persist($typeuser);
                $em->flush();
            }else{
                return $this->view($form->getErrors(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }catch(\Exception $e){
            return $this->view($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->view($typeuser, Response::HTTP_CREATED);
    }

    /**
     * Modifie une typeuser identifié par le paramètre 'id'
     * @Rest\View()
     * @Rest\Put("/typeuser/{id}", requirements={"id" = "\d+"})
     */
    public function putTypeUserAction(Request $request)
    {
        return $this->updateTypeUser($request, true);
    }

    /**
     * Modifie une typeuser identifié par le paramètre 'id'
     *
     * @Rest\View()
     * @Rest\Patch("/typeuser/{id}", requirements={"id" = "\d+"})
     */
    public function patchTypeUserAction(Request $request)
    {
        return $this->updateTypeUser($request, false);
    }

    private function updateTypeUser(Request $request, $clearMissing)
    {
        $response_code = Response::HTTP_OK;
        $em = $this->getDoctrine()->getManager();
        $typeuser = $em->getRepository('AppBundle:TypeUser')->find($request->get('id'));
        if($typeuser == null){
            //$typeuser = new TypeUser();
            //$response_code = Response::HTTP_CREATED;
            return $this->view('Resource not found', Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm(TypeUserType::class, $typeuser);
        $data = json_decode($request->getContent(), true);
        //$data = $this->get('array_converter')->arrayToObject($request->getContent(), TypeUser::class);

        $form->submit($data, $clearMissing);
        try{
            if($form->isValid()) {
                $em->persist($typeuser);
                $em->flush();
            }else{
                return $this->view($form->getErrors(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }catch(\Exception $e){
            return $this->view($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->view($typeuser, $response_code);
    }

    /**
    * Supprime une typeuser identifié par le paramètre 'id'
     *
    * @Rest\View()
    * @Rest\Delete("/typeuser/{id}", requirements={"id" = "\d+"})
    */
    public function deleteTypeUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $typeuser = $em->getRepository('AppBundle:TypeUser')->find($request->get('id'));
        if($typeuser == null){
            return $this->view(false, Response::HTTP_NOT_FOUND);
        }
        $em->remove($typeuser);
        $em->flush();

        return $this->view($typeuser, Response::HTTP_NO_CONTENT);
    }
}
