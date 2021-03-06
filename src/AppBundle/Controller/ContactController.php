<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


use Doctrine\DBAL\DBALException;
use FOS\RestBundle\Controller\FOSRestController;
//TODO : mettre l'entity correspondante au controller
use AppBundle\Entity\Contact;
use AppBundle\Form\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response; // Utilisation de la vue de FOSRestBundle

// TODO: find and replace
//Contact
//contact
//id

class ContactController extends FOSRestController
{

    /**
     * Récupère la liste des contact sur un interval donné selon les paramètres passés
     *
     * @Rest\View()
     * @Rest\Get("/contacts/{start}/{nb}", requirements={"start" = "\d+", "nb" = "\d+"})
     */
    public function getContactAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $params = $request->query->all();

        $contact = $em->getRepository('AppBundle:Contact')->filter($request->get('start'), $request->get('nb'), $params);

        return $this->view($contact, Response::HTTP_OK);
    }

    /**
     * Récupère le nombre de contact sur un interval donné selon les paramètres passés
     * @Rest\View()
     * @Rest\Get("/contacts/count")
     */
    public function getContactCountAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $params = $request->query->all();


        $contact = $em->getRepository('AppBundle:Contact')->getCount($params);


        return $this->view($contact, Response::HTTP_OK);
    }

    /**
     * Récupère un contact identifié par le paramètre 'id'
     *
     * @Rest\View()
     * @Rest\Get("/contacts/{id}", requirements={"id" = "\d+"})
     */
    public function getOneContactAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $contact = $em->getRepository('AppBundle:Contact')->find($request->get('id'));
        if($contact == null){
            return $this->view(false, Response::HTTP_NOT_FOUND);
        }
        return $this->view($contact, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/contacts")
     * @Rest\View(serializerGroups={"ApiContactGroup"})
     */
    public function getAllContactAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $contacts = $em->getRepository('AppBundle:Contact')->findAll();

        return $contacts;
    }

    /**
     * Crée une contact
     *
     * @Rest\View()
     * @Rest\Post("/contacts")
     */
    public function postContactAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        try{
            if($form->isValid()) {
                $em->persist($contact);
                $em->flush();
            }else{
                return $this->view($form->getErrors(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }catch(\Exception $e){
            return $this->view($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->view($contact, Response::HTTP_CREATED);
    }

    /**
     * Modifie une contact identifié par le paramètre 'id'
     * @Rest\View()
     * @Rest\Put("/contacts/{id}", requirements={"id" = "\d+"})
     */
    public function putContactAction(Request $request)
    {
        return $this->updateContact($request, true);
    }

    /**
     * Modifie une contact identifié par le paramètre 'id'
     *
     * @Rest\View()
     * @Rest\Patch("/contacts/{id}", requirements={"id" = "\d+"})
     */
    public function patchContactAction(Request $request)
    {
        return $this->updateContact($request, false);
    }

    private function updateContact(Request $request, $clearMissing)
    {
        $response_code = Response::HTTP_OK;
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository('AppBundle:Contact')->find($request->get('id'));
        if($contact == null){
            //$contact = new Contact();
            //$response_code = Response::HTTP_CREATED;
            return $this->view('Resource not found', Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm(ContactType::class, $contact);
        $data = json_decode($request->getContent(), true);
        //$data = $this->get('array_converter')->arrayToObject($request->getContent(), Contact::class);

        $form->submit($data, $clearMissing);
        try{
            if($form->isValid()) {
                $em->persist($contact);
                $em->flush();
            }else{
                return $this->view($form->getErrors(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }catch(\Exception $e){
            return $this->view($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->view($contact, $response_code);
    }

    /**
     * Supprime une contact identifié par le paramètre 'id'
     *
     * @Rest\View(serializerGroups={"ApiContactGroup"})
     * @Rest\Delete("/contacts/{id}", requirements={"id" = "\d+"})
     */
    public function deleteContactAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository('AppBundle:Contact')->find($request->get('id'));
        if (null === $contact) {
            return $this->view(['code' => Response::HTTP_NOT_FOUND, 'message' => 'Resource not found'], Response::HTTP_NOT_FOUND);
        }

        $em->remove($contact);
        $em->flush();
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
