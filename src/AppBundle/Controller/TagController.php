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
use AppBundle\Form\TagType;

class TagController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"allTags"})
     * @Rest\Get("/tags")
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
     * @Rest\Get("/tags/{tag_id}")
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
    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/tags")
     */
    public function postTagsAction(Request $request)
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');

            $em->persist($tag);
            $em->flush();
            return $tag;
        } else {
            return $form;
        }
    }
    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/tags/{id}")
     */
    public function removeTagAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $tag = $em->getRepository('AppBundle:Tag')
            ->find($request->get('id'));
        /* @var $tag Tag */

        if ($tag) {
            $em->remove($tag);
            $em->flush();
        }
    }
    /**
     * @Rest\View()
     * @Rest\Put("/tags/{id}")
     */
    public function updateTagAction(Request $request)
    {
        return $this->updateTag($request, true);
    }


    /**
     * @Rest\View()
     * @Rest\Patch("/tags/{id}")
     */
    public function patchTagAction(Request $request)
    {
        return $this->updateTag($request, false);
    }

    private function updateTag(Request $request, $clearMissing)
    {
        $tag = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Tag')
            ->find($request->get('id'));
        /* @var $tag Tag */

        if (empty($tag)) {
            return new JsonResponse(['message' => 'Tag not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(TagType::class, $tag);

        $form->submit($request->request->all(), $clearMissing);

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');

            $em->merge($tag);
            $em->flush();
            return $tag;
        } else {
            return $form;
        }
    }
}