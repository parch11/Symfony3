<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * User controller.
 *
 * @Security("is_granted('ROLE_SUPER_ADMIN')")
 * @Route("admin/user")
 */
class AdminUserController extends Controller
{
    /**
     * Lists all user entities.
     *
     * @Route("/", name="admin_user_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->findAll();

        return $this->render('AdminUser/index.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * Creates a new user entity.
     *
     * @Route("/new", name="admin_user_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm('AppBundle\Form\AdminUserNewType', $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //hash du password
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            // Role
            $user->addRole($request->request->get("appbundle_user")["role"]);
            // Enregistrement dans la base
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('admin_user_index');
        }
        return $this->render('AdminUser/edit.html.twig',array(
                'form' => $form->createView(),
                'new' => true
            )
        );
    }


    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/{id}/edit", name="admin_user_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, User $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $user->setPlainPassword("w0Sx10A+dD0cZ15S!Se54seTEA4");
        $editForm = $this->createForm('AppBundle\Form\AdminUserEditType', $user);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_user_index');
        }
        $currentUser = $this->getUser();
        $canEditRole = ($currentUser == $user) ? false : true;
        return $this->render('AdminUser/edit.html.twig', array(
            'user' => $user,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'canEditRole' => $canEditRole,
        ));
    }

    /**
     * Displays a form to edit the role of an existing user entity.
     *
     * @Route("/{id}/role", name="admin_user_role")
     * @Method({"GET", "POST"})
     */
    public function roleAction(Request $request, User $user)
    {
        $currentUser = $this->getUser();
        $canEditRole = ($currentUser == $user) ? false : true;

        if ($canEditRole) {

            $user->setPlainPassword("w0Sx10A+dD0cZ15S!Se54seTEA4");
            $editForm = $this->createForm('AppBundle\Form\AdminUserRoleType', $user);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                // Role
                $user->removeAllRoles();
                $user->addRole($request->request->get("appbundle_user")["role"]);
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('admin_user_edit', array( "id" => $user->getId()));
            }

            return $this->render('AdminUser/role.html.twig', array(
                'user' => $user,
                'form' => $editForm->createView(),
            ));
        }else {
            return $this->redirectToRoute('admin_user_edit', array("id" => $user->getId()));
        }
    }

    /**
     * Displays a form to edit the password of an existing user entity.
     *
     * @Route("/{id}/password", name="admin_user_password")
     * @Method({"GET", "POST"})
     */
    public function passwordAction(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder)
    {
        $editForm = $this->createForm('AppBundle\Form\AdminUserPasswordType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            //hash du password
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_user_edit', array("id" => $user->getId()));
        }

        return $this->render('AdminUser/password.html.twig', array(
            'user' => $user,
            'form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a user entity.
     *
     * @Route("/{id}", name="admin_user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, User $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('admin_user_index');
    }

    /**
     * Creates a form to delete a user entity.
     *
     * @param User $user The user entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
