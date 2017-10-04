<?php

namespace AppBundle\Controller;


use Doctrine\DBAL\DBALException;
use FOS\RestBundle\Controller\FOSRestController;
//TODO : mettre l'entity correspondante au controller
use AppBundle\Entity\Company;
use AppBundle\Form\CompanyType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response; // Utilisation de la vue de FOSRestBundle

// TODO: find and replace
//Company
//companies
//id

class CompanyController extends FOSRestController
{

    /**
     * Récupère la liste des company sur un interval donné selon les paramètres passés
     *
     * @Rest\View()
     * @Rest\Get("/companies/{start}/{nb}", requirements={"start" = "\d+", "nb" = "\d+"})
     */
    public function getCompanyAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $params = $request->query->all();

        $company = $em->getRepository('AppBundle:Company')->filter($request->get('start'), $request->get('nb'), $params);

        return $this->view($company, Response::HTTP_OK);
    }

    /**
     * Récupère le nombre de company sur un interval donné selon les paramètres passés
     * @Rest\View()
     * @Rest\Get("/companies/count")
     */
    public function getCompanyCountAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $params = $request->query->all();


        $company = $em->getRepository('AppBundle:Company')->getCount($params);


        return $this->view($company, Response::HTTP_OK);
    }

    /**
     * Récupère un company identifié par le paramètre 'id'
     *
     * @Rest\View()
     * @Rest\Get("/companies/{id}", requirements={"id" = "\d+"})
     */
    public function getOneCompanyAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $company = $em->getRepository('AppBundle:Company')->find($request->get('id'));
        if($company == null){
            return $this->view(false, Response::HTTP_NOT_FOUND);
        }
        return $this->view($company, Response::HTTP_OK);
    }

    /**
     * Crée une company
     *
     * @Rest\View()
     * @Rest\Post("/companies")
     */
    public function postCompanyAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $company = new Company();

        $form = $this->createForm(CompanyType::class, $company);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        try{
            if($form->isValid()) {
                $em->persist($company);
                $em->flush();
            }else{
                return $this->view($form->getErrors(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }catch(\Exception $e){
            return $this->view($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->view($company, Response::HTTP_CREATED);
    }

    /**
     * Modifie une company identifié par le paramètre 'id'
     * @Rest\View()
     * @Rest\Put("/companies/{id}", requirements={"id" = "\d+"})
     */
    public function putCompanyAction(Request $request)
    {
        return $this->updateCompany($request, true);
    }

    /**
     * Modifie une company identifié par le paramètre 'id'
     *
     * @Rest\View()
     * @Rest\Patch("/companies/{id}", requirements={"id" = "\d+"})
     */
    public function patchCompanyAction(Request $request)
    {
        return $this->updateCompany($request, false);
    }

    private function updateCompany(Request $request, $clearMissing)
    {
        $response_code = Response::HTTP_OK;
        $em = $this->getDoctrine()->getManager();
        $company = $em->getRepository('AppBundle:Company')->find($request->get('id'));
        if($company == null){
            //$company = new Company();
            //$response_code = Response::HTTP_CREATED;
            return $this->view('Resource not found', Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm(CompanyType::class, $company);
        $data = json_decode($request->getContent(), true);
        //$data = $this->get('array_converter')->arrayToObject($request->getContent(), Company::class);

        $form->submit($data, $clearMissing);
        try{
            if($form->isValid()) {
                $em->persist($company);
                $em->flush();
            }else{
                return $this->view($form->getErrors(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }catch(\Exception $e){
            return $this->view($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->view($company, $response_code);
    }

    /**
     * Supprime une company identifié par le paramètre 'id'
     *
     * @Rest\View(serializerGroups={"ApiCompanyGroup"})
     * @Rest\Delete("/companies/{id}", requirements={"id" = "\d+"})
     */
    public function deleteContactAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $companies = $em->getRepository('AppBundle:Company')->find($request->get('id'));
        if (null === $companies) {
            return $this->view(['code' => Response::HTTP_NOT_FOUND, 'message' => 'Resource not found'], Response::HTTP_NOT_FOUND);
        }

        $em->remove($companies);
        $em->flush();
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
