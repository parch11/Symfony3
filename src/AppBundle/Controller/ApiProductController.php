<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest; 
use AppBundle\Entity\Product;
use AppBundle\Form\ApiProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\User;

use Hshn\Base64EncodedFile\HttpFoundation\File\Base64EncodedFile;
use Hshn\Base64EncodedFile\HttpFoundation\File\UploadedBase64EncodedFile; 

class ApiProductController extends Controller
{
    /**
     * @Security("is_granted('ROLE_USER')")
     * @Rest\View(serializerGroups={"allProducts"})
     * @Rest\Get("/api/products")
     */
    public function getProductsAction(Request $request)
    {
        $products = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Product')
            ->findAll(array(), array('createdAt' => 'DESC'));
        /* @var $products Product[] */
        
        return $products;
    }
    /**
     * @Security("is_granted('ROLE_USER')")
     * @Rest\View(serializerGroups={"allProducts"})
     * @Rest\Get("/api/products/created")
     */
    public function getProductsCreatedAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $products = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Product')
            ->findByUser($user, array('createdAt' => 'DESC'));
        /* @var $products Product[] */

        return $products;
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or product.getUser() == user")
     * @Rest\View(serializerGroups={"oneProduct"})
     * @Rest\Get("/api/products/{uuid}")
     */
    public function getProductAction(Request $request, Product $product)
    {
        $product = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Product')
            ->findOneByUuid($request->get('uuid'));
        /* @var $product Product */

        if (empty($product)) {
            return new JsonResponse(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        return $product;
    }
    /**
     * @Security("is_granted('ROLE_USER')")
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"oneProduct"})
     * @Rest\Post("/api/products")
     */
    public function postProductsAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ApiProductType::class, $product);
        if ($request->request->get("img")) {
            $uploadedFile = new UploadedBase64EncodedFile(
                new Base64EncodedFile($request->request->get("img"))
            );
        }
        $form->submit($request->request->all());
        // return $request->request->all();
        if ($form->isValid()) {
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $product->setUser($user);
            $em = $this->get('doctrine.orm.entity_manager');
            $startDate = new \DateTime('now');
            $endDate = new \DateTime('+14 day');
            $product
                ->setCreatedAt($startDate)
                ->setUpdatedAt($startDate)
                ->setAutoDeleteAt($endDate)
                ->setRef(time());

            if ($request->request->get("img")) {
                $product->setImageFile($uploadedFile);
            }
            $em->persist($product);
            $em->flush();
            return $product;
        } else {
            return $form;
        }
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or product.getUser() == user")
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/api/products/{uuid}")
     */
    public function removeProductAction(Request $request, Product $product)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $product = $em->getRepository('AppBundle:Product')
            ->findOneByUuid($request->get('uuid'));
        /* @var $product Product */

        if ($product) {
            $em->remove($product);
            $em->flush();
        }
    }

    /**
     * @Security("is_granted('ROLE_ADMIN') or product.getUser() == user")
     * @Rest\View(serializerGroups={"oneProduct"})
     * @Rest\Patch("/api/products/{uuid}")
     */
    public function patchProductAction(Request $request, Product $product)
    {
        $product = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Product')
            ->findOneByUuid($request->get('uuid'));
        /* @var $product Product */

        if (empty($product)) {
            return new JsonResponse(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(ApiProductType::class, $product);
        if ($request->request->get("img")) {
            $uploadedFile = new UploadedBase64EncodedFile(
                new Base64EncodedFile($request->request->get("img"))
            );
        }
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $updateDate = new \DateTime('now');
            $product->setUpdatedAt($updateDate);

            $em = $this->get('doctrine.orm.entity_manager');
            if ($request->request->get("img")) {
                $product->setImageFile($uploadedFile);
            }
            $em->merge($product);
            $em->flush();
            return $product;
        } else {
            return $form;
        }
    }
}