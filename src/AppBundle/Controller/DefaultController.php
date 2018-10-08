<?php
namespace AppBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
     * @Route("/admin", name="admin_index")
     */
    public function adminIndexAction()
    {
        return $this->render('Admin/index.html.twig');
    }
}