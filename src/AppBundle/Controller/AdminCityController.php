<?php

namespace AppBundle\Controller;

use AppBundle\Entity\City;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\User;

/**
 * City controller.
 *
 * @Security("is_granted('ROLE_SUPER_ADMIN')")
 * @Route("admin/city")
 */
class AdminCityController extends Controller
{
    /**
     * Lists all city entities.
     *
     * @Route("/", name="admin_city_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $cities = $em->getRepository('AppBundle:City')->findAll();

        return $this->render('AdminCity/index.html.twig', array(
            'cities' => $cities,
        ));
    }

    /**
     * Creates a new city entity.
     *
     * @Route("/new", name="admin_city_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $city = new City();
        $form = $this->createForm('AppBundle\Form\AdminCityType', $city);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($city);
            $em->flush();

            return $this->redirectToRoute('admin_city_index');
        }

        return $this->render('AdminCity/new.html.twig', array(
            'city' => $city,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing city entity.
     *
     * @Route("/{id}/edit", name="admin_city_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, City $city)
    {
        $deleteForm = $this->createDeleteForm($city);
        $editForm = $this->createForm('AppBundle\Form\AdminCityType', $city);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_city_index');
        }

        return $this->render('AdminCity/edit.html.twig', array(
            'city' => $city,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a city entity.
     *
     * @Route("/{id}", name="admin_city_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, City $city)
    {
        $form = $this->createDeleteForm($city);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($city);
            $em->flush();
        }

        return $this->redirectToRoute('admin_city_index');
    }

    /**
     * Creates a form to delete a city entity.
     *
     * @param City $city The city entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(City $city)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_city_delete', array('id' => $city->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
