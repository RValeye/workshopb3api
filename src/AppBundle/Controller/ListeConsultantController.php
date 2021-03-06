<?php

namespace AppBundle\Controller;


use Doctrine\DBAL\DBALException;
use FOS\RestBundle\Controller\FOSRestController;
//TODO : mettre l'entity correspondante au controller
use AppBundle\Entity\ListeConsultant;
use AppBundle\Form\ListeConsultantType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response; // Utilisation de la vue de FOSRestBundle

// TODO: find and replace
//ListeConsultant
//listeconsultant
//id

class ListeConsultantController extends FOSRestController
{

    /**
     * Récupère la liste des listeconsultant sur un interval donné selon les paramètres passés
     *
     * @Rest\View(serializerGroups={"ApiListeConsultantGroup"})
     * @Rest\Get("/listeconsultants/{start}/{nb}", requirements={"start" = "\d+", "nb" = "\d+"})
     */
    public function getListeConsultantAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $params = $request->query->all();

        $listeconsultant = $em->getRepository('AppBundle:ListeConsultant')->filter($request->get('start'), $request->get('nb'), $params);

        return $this->view($listeconsultant, Response::HTTP_OK);
    }

    /**
     * Récupère le nombre de listeconsultant sur un interval donné selon les paramètres passés
     * @Rest\View(serializerGroups={"ApiListeConsultantGroup"})
     * @Rest\Get("/listeconsultants/count")
     */
    public function getListeConsultantCountAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $params = $request->query->all();


        $listeconsultant = $em->getRepository('AppBundle:ListeConsultant')->getCount($params);


        return $this->view($listeconsultant, Response::HTTP_OK);
    }

    /**
     * Récupère un listeconsultant identifié par le paramètre 'id'
     *
     * @Rest\View(serializerGroups={"ApiListeConsultantGroup"})
     * @Rest\Get("/listeconsultants/{id}", requirements={"id" = "\d+"})
     */
    public function getOneListeConsultantAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $listeconsultant = $em->getRepository('AppBundle:ListeConsultant')->find($request->get('id'));
        if($listeconsultant == null){
            return $this->view(false, Response::HTTP_NOT_FOUND);
        }
        return $this->view($listeconsultant, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/listeconsultants")
     * @Rest\View(serializerGroups={"ApiListeConsultantGroup"})
     */
    public function getAllListeConsultantAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $listeconsultant = $em->getRepository('AppBundle:ListeConsultant')->findAll();

        return $listeconsultant;
    }

    /**
     * Crée une listeconsultant
     *
     * @Rest\View(serializerGroups={"ApiListeConsultantGroup"})
     * @Rest\Post("/listeconsultants")
     */
    public function postListeConsultantAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $listeconsultant = new ListeConsultant();

        $form = $this->createForm(ListeConsultantType::class, $listeconsultant);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        try{
            if($form->isValid()) {
                $em->persist($listeconsultant);
                $em->flush();
            }else{
                return $this->view($form->getErrors(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }catch(\Exception $e){
            return $this->view($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->view($listeconsultant, Response::HTTP_CREATED);
    }

    /**
     * Modifie une listeconsultant identifié par le paramètre 'id'
     * @Rest\View(serializerGroups={"ApiListeConsultantGroup"})
     * @Rest\Put("/listeconsultants/{id}", requirements={"id" = "\d+"})
     */
    public function putListeConsultantAction(Request $request)
    {
        return $this->updateListeConsultant($request, true);
    }

    /**
     * Modifie une listeconsultant identifié par le paramètre 'id'
     *
     * @Rest\View(serializerGroups={"ApiListeConsultantGroup"})
     * @Rest\Patch("/listeconsultants/{id}", requirements={"id" = "\d+"})
     */
    public function patchListeConsultantAction(Request $request)
    {
        return $this->updateListeConsultant($request, false);
    }

    private function updateListeConsultant(Request $request, $clearMissing)
    {
        $response_code = Response::HTTP_OK;
        $em = $this->getDoctrine()->getManager();
        $listeconsultant = $em->getRepository('AppBundle:ListeConsultant')->find($request->get('id'));
        if($listeconsultant == null){
            //$listeconsultant = new ListeConsultant();
            //$response_code = Response::HTTP_CREATED;
            return $this->view('Resource not found', Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm(ListeConsultantType::class, $listeconsultant);
        $data = json_decode($request->getContent(), true);
        //$data = $this->get('array_converter')->arrayToObject($request->getContent(), ListeConsultant::class);

        $form->submit($data, $clearMissing);
        try{
            if($form->isValid()) {
                $em->persist($listeconsultant);
                $em->flush();
            }else{
                return $this->view($form->getErrors(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }catch(\Exception $e){
            return $this->view($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->view($listeconsultant, $response_code);
    }

    /**
     * Supprime une listeconsultant identifié par le paramètre 'id'
     *
     * @Rest\View(serializerGroups={"ApiListeConsultantGroup"})
     * @Rest\Delete("/listeconsultants/{id}", requirements={"id" = "\d+"})
     */
    public function deleteListeConsultantAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $listeconsultant = $em->getRepository('AppBundle:ListeConsultant')->find($request->get('id'));
        if (null === $listeconsultant) {
            return $this->view(['code' => Response::HTTP_NOT_FOUND, 'message' => 'Resource not found'], Response::HTTP_NOT_FOUND);
        }

        $em->remove($listeconsultant);
        $em->flush();
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}

