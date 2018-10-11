<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class NavbarController extends Controller
{
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('AppBundle:Category')->findAll();
        $tags = $em->getRepository('AppBundle:Tag')->findAll();
        // $currentRoute = $this->container->get('request_stack')->getMasterRequest()->get('_route');
        $currentUrl = $this->container->get('request_stack')->getMasterRequest()->getRequestUri();

        return $this->render('Navbar/navbar.html.twig', array(
            'categories' => $categories,
            'currentUrl' => $currentUrl,
            'tags' => $tags
        ));
    }
}
