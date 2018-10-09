<?php
// src/Controller/RegistrationController.php
namespace AppBundle\Controller;

use AppBundle\Form\UserType;
use AppBundle\Entity\User;
use AppBundle\Events;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class RegistrationController extends Controller
{
    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Route("/admin/register", name="user_registration")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, EventDispatcherInterface $eventDispatcher)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
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
            return $this->redirectToRoute('security_login');
        }
        return $this->render(
            'Security/register.html.twig',
            array('form' => $form->createView())
        );
    }
}