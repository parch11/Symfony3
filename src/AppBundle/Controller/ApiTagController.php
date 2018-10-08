<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest; 
use AppBundle\Entity\Tag;
use AppBundle\Form\ApiTagType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ApiTagController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"allTags"})
     * @Rest\Get("/api/tags")
     */
    public function getTagsAction(Request $request)
    {
        $tags = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Tag')
            ->findAll();
        /* @var $tags Tag[] */
        // dump($tags);
        
        return $tags;
    }


    /**
     * @Rest\View(serializerGroups={"oneTag"})
     * @Rest\Get("/api/tags/{tag_id}")
     */
    public function getTagAction(Request $request)
    {
        $tag = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Tag')
            ->find($request->get('tag_id'));
        /* @var $tag Tag */

        if (empty($tag)) {
            return new JsonResponse(['message' => 'Tag not found'], Response::HTTP_NOT_FOUND);
        }

        return $tag;
    }
}