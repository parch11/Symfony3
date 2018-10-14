<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest; 
use AppBundle\Entity\Category;
use AppBundle\Form\ApiCategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ApiCategoryController extends Controller
{
    /**
     * @Security("is_granted('ROLE_USER')")
     * @Rest\View(serializerGroups={"allCat"})
     * @Rest\Get("/api/categories")
     */
    public function getCategoriesAction(Request $request)
    {
        $categories = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Category')
            ->findAll();
        /* @var $categories Category[] */
        // dump($categories);
        
        return $categories;
    }


    /**
     * @Security("is_granted('ROLE_USER')")
     * @Rest\View(serializerGroups={"oneCat"})
     * @Rest\Get("/api/categories/{category_id}")
     */
    public function getCategoryAction(Request $request)
    {
        $category = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Category')
            ->find($request->get('category_id'));
        /* @var $category Category */

        if (empty($category)) {
            return new JsonResponse(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

        return $category;
    }
}