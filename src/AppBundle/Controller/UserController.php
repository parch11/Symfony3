<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest; // alias pour toutes les annotations
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UserController extends Controller
{
    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Rest\View(serializerGroups={"allUsers"})
     * @Rest\Get("/users")
     */
    public function getUsersAction(Request $request)
    {
        $users = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:User')
            ->findAll();
        /* @var $users User[] */
        // dump($users);
        
        return $users;
    }


    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or userTarget == user")
     * @Rest\View(serializerGroups={"oneUser"})
     * @Rest\Get("/users/{id}")
     */
    public function getUserAction(Request $request, User $userTarget)
    {
        $userTarget = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:User')
            ->find($request->get('id'));
        /* @var $userTarget User */

        if (empty($userTarget)) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return $userTarget;
    }
    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"oneUser"})
     * @Rest\Post("/users")
     */
    public function postUsersAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->addRole($request->request->get("role"));
            $em->persist($user);
            $em->flush();
            return $user;
        } else {
            return $form;
        }
    }
    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/users/{id}")
     */
    public function removeUserAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $user = $em->getRepository('AppBundle:User')
            ->find($request->get('id'));
        /* @var $user User */

        if ($user) {
            $em->remove($user);
            $em->flush();
        }
    }
    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Rest\View()
     * @Rest\Put("/users/{id}")
     */
    public function updateUserAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        return $this->updateUser($request, true, $passwordEncoder);
    }


    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN') or userTarget == user")
     * @Rest\View(serializerGroups={"oneUser"})
     * @Rest\Patch("/users/{id}")
     */
    public function patchUserAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, User $userTarget)
    {
        return $this->updateUser($request, false, $passwordEncoder);
    }

    private function updateUser(Request $request, $clearMissing, $passwordEncoder)
    {
        $user = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:User')
            ->find($request->get('id'));
        /* @var $user User */

        if (empty($user)) {
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(UserType::class, $user);

        $form->submit($request->request->all(), $clearMissing);

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            if (!empty($request->request->get("role")) && $request->request->get("role") != "") {
                $user->removeAllRoles();
                $user->addRole($request->request->get("role"));
            }
            if (!empty($request->request->get("plainPassword")) && $request->request->get("plainPassword") != "") {
                $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
            }
            $em->merge($user);
            $em->flush();
            return $user;
        } else {
            return $form;
        }
    }
}