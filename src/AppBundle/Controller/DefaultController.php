<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest; 

class DefaultController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Get("/")
     */
    public function indexAction(Request $request)
    {
        return new JsonResponse(['message' => 'Rien à cette adresse'], Response::HTTP_NOT_FOUND);
    }
}
