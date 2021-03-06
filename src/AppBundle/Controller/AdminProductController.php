<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\User;

/**
 * Product controller.
 *
 * @Route("admin/product")
 */
class AdminProductController extends Controller
{
    /**
     * Lists all product entities.
     *
     * @Security("is_granted('ROLE_ADMIN')")
     * @Route("/", name="admin_product_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('AppBundle:Product')->findAll(array(), array('createdAt' => 'DESC'));

        return $this->render('AdminProduct/index.html.twig', array(
            'products' => $products,
        ));
    }

    /**
     * Lists all product entities created by the user.
     *
     * @Security("is_granted('ROLE_USER')")
     * @Route("/created", name="admin_product_created")
     * @Method("GET")
     */
    public function showCreatedAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $products = $em->getRepository('AppBundle:Product')->findByUser($user, array('createdAt' => 'DESC'));

        return $this->render('AdminProduct/index.html.twig', array(
            'products' => $products,
            'onlyCreated' => true,
            'username' => $user->getUsername(),
        ));
    }
    /**
     * Lists all product entities created by one user.
     *
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Route("/user/{uuid}/created", name="admin_product_by_user")
     * @Method("GET")
     */
    public function showUserProductAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('AppBundle:Product')->findByUser($user, array('createdAt' => 'DESC'));

        return $this->render('AdminProduct/index.html.twig', array(
            'products' => $products,
            'onlyCreated' => true,
            'username' => $user->getUsername(),
        ));
    }
    /**
     * Creates a new product entity.
     *
     * @Security("is_granted('ROLE_USER')")
     * @Route("/new", name="admin_product_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $product = new Product();

        $startDate = new \DateTime('now');
        $endDate = new \DateTime('+14 day');
        $product
            ->setCreatedAt($startDate)
            ->setUpdatedAt($startDate)
            ->setAutoDeleteAt($endDate)
            ->setRef(time());
        
        $form = $this->createForm('AppBundle\Form\AdminProductType', $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $product->setUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('admin_product_created');
        }

        return $this->render('AdminProduct/edit.html.twig', array(
            'product' => $product,
            'form' => $form->createView(),
            'new' => true,
        ));
    }

    /**
     * Displays a form to edit an existing product entity.
     *
     * @Security("is_granted('ROLE_ADMIN') or product.getUser() == user")
     * @Route("/{uuid}/edit", name="admin_product_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);
        $editForm = $this->createForm('AppBundle\Form\AdminProductType', $product);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_product_created');
        }

        return $this->render('AdminProduct/edit.html.twig', array(
            'product' => $product,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a product entity.
     *
     * @Security("is_granted('ROLE_ADMIN') or product.getUser() == user")
     * @Route("/{uuid}", name="admin_product_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Product $product)
    {
        $form = $this->createDeleteForm($product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();
        }

        return $this->redirectToRoute('admin_product_created');
    }

    /**
     * Creates a form to delete a product entity.
     *
     * @param Product $product The product entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Product $product)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_product_delete', array('uuid' => $product->getUuid())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
