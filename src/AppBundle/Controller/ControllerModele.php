<?php

namespace AppBundle\Controller;

//use Doctrine\DBAL\DBALException;
//use FOS\RestBundle\Controller\FOSRestController;
////TODO : mettre l'entity correspondante au controller
//use AppBundle\Entity\NomEntity;
//use AppBundle\Form\NomEntityType;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
//use Symfony\Component\HttpFoundation\Request;
//use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
//use FOS\RestBundle\View\ViewHandler;
//use FOS\RestBundle\View\View;
//use Symfony\Component\HttpFoundation\Response; // Utilisation de la vue de FOSRestBundle
//
//// TODO: find and replace
////NomEntity
////nomentity
////indexofentity
//
//class NomEntityController extends FOSRestController
//{
//
//    /**
//     * Récupère la liste des nomentity sur un interval donné selon les paramètres passés
//     *
//     * @Rest\View(serializerGroups={"ApiNomEntityGroup"})
//     * @Rest\Get("/nomentity/{start}/{nb}", requirements={"start" = "\d+", "nb" = "\d+"})
//     */
//    public function getNomEntityAction(Request $request)
//    {
//        $em = $this->getDoctrine()->getManager();
//
//        $params = $request->query->all();
//
//        $nomentity = $em->getRepository('AppBundle:NomEntity')->filter($request->get('start'), $request->get('nb'), $params);
//
//        return $this->view($nomentity, Response::HTTP_OK);
//    }
//
//    /**
//     * Récupère le nombre de nomentity sur un interval donné selon les paramètres passés
//     * @Rest\View(serializerGroups={"ApiNomEntityGroup"})
//     * @Rest\Get("/nomentity/count")
//     */
//    public function getNomEntityCountAction(Request $request)
//    {
//        $em = $this->getDoctrine()->getManager();
//
//        $params = $request->query->all();
//
//
//        $nomentity = $em->getRepository('AppBundle:NomEntity')->getCount($params);
//
//
//        return $this->view($nomentity, Response::HTTP_OK);
//    }
//
//    /**
//     * Récupère un nomentity identifié par le paramètre 'indexofentity'
//     *
//     * @Rest\View(serializerGroups={"ApiNomEntityGroup"})
//     * @Rest\Get("/nomentity/{id}", requirements={"id" = "\d+"}
//     */
//    public function getOneNomEntityAction(Request $request)
//    {
//        $em = $this->getDoctrine()->getManager();
//
//        $nomentity = $em->getRepository('AppBundle:NomEntity')->find($request->get('indexofentity'));
//        if($nomentity == null){
//            return $this->view(false, Response::HTTP_NOT_FOUND);
//        }
//        return $this->view($nomentity, Response::HTTP_OK);
//    }
//
//    /**
//     * Crée une nomentity
//     *
//     * @Rest\View(serializerGroups={"ApiNomEntityGroup"})
//     * @Rest\Post("/nomentity")
//     */
//    public function postNomEntityAction(Request $request)
//    {
//        $em = $this->getDoctrine()->getManager();
//        $nomentity = new NomEntity();
//
//        $form = $this->createForm(NomEntityType::class, $nomentity);
//        $data = json_decode($request->getContent(), true);
//        $form->submit($data);
//
//        try{
//            if($form->isValid()) {
//                $em->persist($nomentity);
//                $em->flush();
//            }else{
//                return $this->view($form->getErrors(), Response::HTTP_BAD_REQUEST);
//            }
//        }catch(DBALException $e){
//            return $this->view($e->getMessage(), Response::HTTP_BAD_REQUEST);
//        }
//        return $this->view($nomentity, Response::HTTP_CREATED);
//    }
//
//    /**
//     * Modifie une nomentity identifié par le paramètre 'indexofentity'
//     * @Rest\View(serializerGroups={"ApiNomEntityGroup"})
//     * @Rest\Put("/nomentity/{id}", requirements={"id" = "\d+"}
//     */
//    public function putNomEntityAction(Request $request)
//    {
//        return $this->updateNomEntity($request, true);
//    }
//
//    /**
//     * Modifie une nomentity identifié par le paramètre 'indexofentity'
//     *
//     * @Rest\View(serializerGroups={"ApiNomEntityGroup"})
//     * @Rest\Patch("/nomentity/{id}", requirements={"id" = "\d+"}
//     */
//    public function patchNomEntityAction(Request $request)
//    {
//        return $this->updateNomEntity($request, false);
//    }
//
//    private function updateNomEntity(Request $request, $clearMissing)
//    {
//        $response_code = Response::HTTP_OK;
//        $em = $this->getDoctrine()->getManager();
//        $nomentity = $em->getRepository('AppBundle:NomEntity')->find($request->get('indexofentity'));
//        if($nomentity == null){
//            //$nomentity = new NomEntity();
//            //$response_code = Response::HTTP_CREATED;
//            return $this->view('Resource not found', Response::HTTP_NOT_FOUND);
//        }
//        $form = $this->createForm(NomEntityType::class, $nomentity);
//        $data = json_decode($request->getContent(), true);
//        //$data = $this->get('array_converter')->arrayToObject($request->getContent(), NomEntity::class);
//
//        $form->submit($data, $clearMissing);
//        try{
//            if($form->isValid()) {
//                $em->persist($nomentity);
//                $em->flush();
//            }else{
//                return $this->view($form->getErrors(), Response::HTTP_BAD_REQUEST);
//            }
//        }catch(DBALException $e){
//            return $this->view($e->getMessage(), Response::HTTP_BAD_REQUEST);
//        }
//
//        return $this->view($nomentity, $response_code);
//    }
//
//    /**
//     * Supprime une nomentity identifié par le paramètre 'indexofentity'
//     *
//     * @Rest\View()
//     * @Rest\Delete("/nomentity/{id}", requirements={"id" = "\d+"}
//     */
//    public function deleteNomEntityAction(Request $request)
//    {
//        $em = $this->getDoctrine()->getManager();
//        $nomentity = $em->getRepository('AppBundle:NomEntity')->find($request->get('indexofentity'));
//        if (null === $nomentity) {
//            return $this->view(['code' => Response::HTTP_NOT_FOUND, 'message' => 'Resource not found'], Response::HTTP_NOT_FOUND);
//        }
//
//        $em->remove($nomentity);
//        $em->flush();
//        return $this->view(null, Response::HTTP_NO_CONTENT);
//    }
//}
