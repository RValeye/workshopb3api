<?php

namespace AppBundle\Controller;

use Doctrine\DBAL\DBALException;
use FOS\RestBundle\Controller\FOSRestController;
//TODO : mettre l'entity correspondante au controller
use AppBundle\Entity\Besoin;
use AppBundle\Form\BesoinType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response; // Utilisation de la vue de FOSRestBundle

// TODO: find and replace
//Besoin
//besoin
//id

class BesoinController extends FOSRestController
{

    /**
     * Récupère la liste des besoin sur un interval donné selon les paramètres passés
     *
     * @Rest\View(serializerGroups={"ApiBesoinGroup"})
     * @Rest\Get("/besoins/{start}/{nb}", requirements={"start" = "\d+", "nb" = "\d+"})
     */
    public function getBesoinAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $params = $request->query->all();

        $besoin = $em->getRepository('AppBundle:Besoin')->filter($request->get('start'), $request->get('nb'), $params);

        return $this->view($besoin, Response::HTTP_OK);
    }

    /**
     * Récupère le nombre de besoin sur un interval donné selon les paramètres passés
     * @Rest\View(serializerGroups={"ApiBesoinGroup"})
     * @Rest\Get("/besoins/count")
     */
    public function getBesoinCountAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $params = $request->query->all();


        $besoin = $em->getRepository('AppBundle:Besoin')->getCount($params);


        return $this->view($besoin, Response::HTTP_OK);
    }

    /**
     * Récupère un besoin identifié par le paramètre 'id'
     *
     * @Rest\View(serializerGroups={"ApiBesoinGroup"})
     * @Rest\Get("/besoins/{id}", requirements={"id" = "\d+"})
     */
    public function getOneBesoinAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $besoin = $em->getRepository('AppBundle:Besoin')->find($request->get('id'));
        if($besoin == null){
            return $this->view(false, Response::HTTP_NOT_FOUND);
        }
        return $this->view($besoin, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/besoins")
     * @Rest\View(serializerGroups={"ApiBesoinGroup"})
     */
    public function getAllBesoinAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:Besoin')->getAll($request->query->all());

        return $users;
    }

    /**
     * Crée une besoin
     *
     * @Rest\View(serializerGroups={"ApiBesoinGroup"})
     * @Rest\Post("/besoins")
     */
    public function postBesoinAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $besoin = new Besoin();

        $form = $this->createForm(BesoinType::class, $besoin);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        try{
            if($form->isValid()) {
                $em->persist($besoin);
                $em->flush();
            }else{
                return $this->view($form->getErrors(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }catch(\Exception $e){
            return $this->view($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->view($besoin, Response::HTTP_CREATED);
    }

    /**
     * @Rest\View(serializerGroups={"ApiBesoinGroup"})
     * @Rest\Patch("/besoins/{id}/file", requirements={"id" = "\d+"})
     */
    public function patchBesoinFileAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $besoin = $em->getRepository('AppBundle:Besoin')->find($request->get('id'));
        if($besoin == null){
            //$besoin = new Besoin();
            //$response_code = Response::HTTP_CREATED;
            return $this->view('Resource not found', Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Modifie une besoin identifié par le paramètre 'id'
     * @Rest\View(serializerGroups={"ApiBesoinGroup"})
     * @Rest\Put("/besoins/{id}", requirements={"id" = "\d+"})
     */
    public function putBesoinAction(Request $request)
    {
        return $this->updateBesoin($request, true);
    }

    /**
     * Modifie une besoin identifié par le paramètre 'id'
     *
     * @Rest\View(serializerGroups={"ApiBesoinGroup"})
     * @Rest\Patch("/besoins/{id}", requirements={"id" = "\d+"})
     */
    public function patchBesoinAction(Request $request)
    {
        return $this->updateBesoin($request, false);
    }

    private function updateBesoin(Request $request, $clearMissing)
    {
        $response_code = Response::HTTP_OK;
        $em = $this->getDoctrine()->getManager();
        $besoin = $em->getRepository('AppBundle:Besoin')->find($request->get('id'));
        if($besoin == null){
            //$besoin = new Besoin();
            //$response_code = Response::HTTP_CREATED;
            return $this->view('Resource not found', Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm(BesoinType::class, $besoin);
        $data = json_decode($request->getContent(), true);
        //$data = $this->get('array_converter')->arrayToObject($request->getContent(), Besoin::class);

        $form->submit($data, $clearMissing);
        try{
            if($form->isValid()) {
                $em->persist($besoin);
                $em->flush();
            }else{
                return $this->view($form->getErrors(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }catch(\Exception $e){
            return $this->view($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->view($besoin, $response_code);
    }

    /**
     * Supprime une besoin identifié par le paramètre 'id'
     *
     * @Rest\View()
     * @Rest\Delete("/besoins/{id}", requirements={"id" = "\d+"})
     */
    public function deleteBesoinAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $besoin = $em->getRepository('AppBundle:Besoin')->find($request->get('id'));
        if (null === $besoin) {
            return $this->view(['code' => Response::HTTP_NOT_FOUND, 'message' => 'Resource not found'], Response::HTTP_NOT_FOUND);
        }

        $besoin->setActive(false);
        $em->persist($besoin);
        //$em->remove($besoin);
        $em->flush();
        return $this->view($besoin, Response::HTTP_OK);
    }
}
