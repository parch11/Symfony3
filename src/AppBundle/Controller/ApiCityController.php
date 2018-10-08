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

class ApiCityController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"allCities"})
     * @Rest\Get("/api/cities")
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
     * @Rest\Get("/api/cities/{city_id}")
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
}