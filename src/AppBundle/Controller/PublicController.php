<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Tag;
use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Public controller.
 */
class PublicController extends Controller
{
    /**
     * Finds and displays all products from the selected category.
     *
     * @Route("category/{id}", name="category_products")
     * @Method("GET")
     */
    public function showCategoryProductsAction(Category $category)
    {

        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('AppBundle:Product')->findByCategory($category);

        return $this->render('Public/showCategoryProducts.html.twig', array(
            'categoryName' => $category->getName(),
            'products' => $products,
        ));
    }

    /**
     * Finds and displays a product entity.
     *
     * @Route("product/{id}", name="product_show")
     * @Method("GET")
     */
    public function showAction(Product $product)
    {
        return $this->render('Public/show.html.twig', array(
            'product' => $product,
        ));
    }
    /**
     * Finds and displays all products from the selected tag.
     *
     * @Route("tag/{id}", name="tag_products")
     * @Method("GET")
     */
    public function showTagProductsAction(Tag $tag)
    {
        return $this->render('Public/showTagProducts.html.twig', array(
            'tag' => $tag,
        ));
    }
}
