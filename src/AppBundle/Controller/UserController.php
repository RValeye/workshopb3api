<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Doctrine\DBAL\DBALException;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends FOSRestController
{
    /**
     * @Rest\Get("/users/{start}/{nb}", requirements={"start" = "\d+", "nb" = "\d+"})
     * @Rest\View(serializerGroups={"ApiUserGroup"})
     */
    public function getUsersAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->filter($request->get('start'), $request->get('nb'), $request->query->all());

        return $users;
    }

    /**
     * @Rest\Get("/users/{id}", requirements={"id" = "\d+"})
     * @Rest\View(serializerGroups={"ApiUserGroup"})
     */
    public function getOneUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')->find($request->get('id'));

        if (null !== $user) {
            return $user;
        }
        return $this->view(['code' => Response::HTTP_NOT_FOUND, 'message' => 'Resource not found'], Response::HTTP_NOT_FOUND);
    }

    /**
     * @Rest\Get("/users")
     * @Rest\View(serializerGroups={"ApiUserGroup"})
     */
    public function getAllUsersAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->findAll();

        return $users;
    }

    /**
     * @Rest\Post("/users")
     * @Rest\View(serializerGroups={"ApiUserGroup"})
     */
    public function postUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $data = json_decode($request->getContent(), true);

        $uuid = $this->generateUUID();

        $data['apikey'] = $uuid;

        $form->submit($data);

        try{
            if($form->isValid()) {
                $em->persist($user);
                $em->flush();
            }else{
                return $this->view($form->getErrors(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }catch(\Exception $e){
            return $this->view($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->view($user, Response::HTTP_CREATED);

    }

    /**
     * @Rest\Put("/users/{id}", requirements={"id" = "\d+"})
     * @Rest\View(serializerGroups={"ApiUserGroup"})
     */
    public function putUserAction(Request $request)
    {
        return $this->updateUser($request, true);
    }

    /**
     * @Rest\Patch("/users/{id}", requirements={"id" = "\d+"})
     * @Rest\View(serializerGroups={"ApiUserGroup"})
     */
    public function patchUserAction(Request $request)
    {
        return $this->updateUser($request, false);
    }

    private function updateUser(Request $request, $clearMissing = true)
    {
        $response_code = Response::HTTP_OK;
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($request->get('id'));
        if($user === null){
            //$clients = new Clients();
            //$response_code = Response::HTTP_CREATED;
            return $this->view('Resource not found', Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm(UserType::class, $user);
        $data = json_decode($request->getContent(), true);
        //$data = $this->get('array_converter')->arrayToObject($request->getContent(), Clients::class);

        $form->submit($data, $clearMissing);
        try{
            if($form->isValid()) {
                $em->persist($user);
                $em->flush();
            }else{
                return $this->view($form->getErrors(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }catch(\Exception $e){
            return $this->view($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->view($user, $response_code);
    }

    /**
     * @Rest\Delete("/users/{id}", requirements={"id" = "\d+"})
     * @Rest\View()
     */
    public function deleteUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')->find($request->get('id'));
        if (null === $user) {
            return $this->view(['code' => Response::HTTP_NOT_FOUND, 'message' => 'Resource not found'], Response::HTTP_NOT_FOUND);
        }

        $em->remove($user);
        $em->flush();
        return $this->view(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Rest\Patch("/users/{id}/refresh-apikey", requirements={"id" = "\d+"})
     * @Rest\View(serializerGroups={"ApiUserGroup"})
     */
    public function patchUpdateApikeyAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')->find($request->get('id'));
        if (null === $user) {
            return $this->view(['code' => Response::HTTP_NOT_FOUND, 'message' => 'Resource not found'], Response::HTTP_NOT_FOUND);
        }

        try {
            $key = $this->generateUUID();
            $user->setApikey($key);
            $em->persist($user);
            $em->flush();
            return $user;
        } catch (\Exception $exception) {
            return $this->view(['code' => Response::HTTP_INTERNAL_SERVER_ERROR, 'message' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    private function generateUUID() {
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"

        return substr($charid, 0, 5).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12);
    }
}
