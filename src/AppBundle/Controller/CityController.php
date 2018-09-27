<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest; 
use AppBundle\Entity\City;
use AppBundle\Form\CityType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class CityController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"allCities"})
     * @Rest\Get("/cities")
     */
    public function getCitiesAction(Request $request)
    {
        $cities = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:City')
            ->findAll();
        /* @var $cities City[] */
        // dump($cities);
        
        return $cities;
    }


    /**
     * @Rest\View(serializerGroups={"oneCity"})
     * @Rest\Get("/cities/{city_id}")
     */
    public function getCityAction(Request $request)
    {
        $city = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:City')
            ->find($request->get('city_id'));
        /* @var $city City */

        if (empty($city)) {
            return new JsonResponse(['message' => 'City not found'], Response::HTTP_NOT_FOUND);
        }

        return $city;
    }
    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"oneCity"})
     * @Rest\Post("/cities")
     */
    public function postCitiesAction(Request $request)
    {
        $city = new City();
        $form = $this->createForm(CityType::class, $city);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');

            $em->persist($city);
            $em->flush();
            return $city;
        } else {
            return $form;
        }
    }
    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/cities/{id}")
     */
    public function removeCityAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $city = $em->getRepository('AppBundle:City')
            ->find($request->get('id'));
        /* @var $city City */

        if ($city) {
            $em->remove($city);
            $em->flush();
        }
    }
    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Rest\View()
     * @Rest\Put("/cities/{id}")
     */
    public function updateCityAction(Request $request)
    {
        return $this->updateCity($request, true);
    }


    /**
     * @Security("is_granted('ROLE_SUPER_ADMIN')")
     * @Rest\View(serializerGroups={"oneCity"})
     * @Rest\Patch("/cities/{id}")
     */
    public function patchCityAction(Request $request)
    {
        return $this->updateCity($request, false);
    }

    private function updateCity(Request $request, $clearMissing)
    {
        $city = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:City')
            ->find($request->get('id'));
        /* @var $city City */

        if (empty($city)) {
            return new JsonResponse(['message' => 'City not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(CityType::class, $city);

        $form->submit($request->request->all(), $clearMissing);

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');

            $em->merge($city);
            $em->flush();
            return $city;
        } else {
            return $form;
        }
    }
}