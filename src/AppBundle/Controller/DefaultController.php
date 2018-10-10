<?php
namespace AppBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        return $this->render('index.html.twig');
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     * @Route("/admin", name="admin_index")
     */
    public function adminIndexAction()
    {
        return $this->render('Admin/index.html.twig');
    }

    /**
     * Displays a form to edit the password of an existing user entity.
     *
     * @Security("is_granted('ROLE_USER')")
     * @Route("/admin/password", name="user_password")
     * @Method({"GET", "POST"})
     */
    public function passwordAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getUser();
        $editForm = $this->createForm('AppBundle\Form\AdminUserPasswordType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            //hash du password
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect('/logout');
        }

        return $this->render('Admin/password.html.twig', array(
            'user' => $user,
            'form' => $editForm->createView(),
        ));
    }
}