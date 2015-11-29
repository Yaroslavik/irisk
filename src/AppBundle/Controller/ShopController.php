<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 05.11.15
 * Time: 16:36
 */

namespace AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShopController extends Controller
{
    /**
     * @Route("/catalog/{path}", name="catalog", requirements={"path": ".+"})
     * @Template()
     */
    public function catalogAction($path = null)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $r = $em->getRepository('AxSShopBundle:ShopCategory');

        if ($path === null) {
            return ['subcategories' => $r->findBy(['visible' => 1, 'parent' => null], ['order' => 'asc'])];
        }

        // Parse path and find current category
        $parts = preg_split('/\\//', $path);
        $last = end($parts);

        $category = $r->findOneBy([
            'slug' => $last,
            'visible' => 1,
        ]);

        if (!$category) throw new NotFoundHttpException();

        // If not contains subcategories forward to products page
        if (!count($category->getChildren())) {
            return $this->forward('AppBundle:Shop:products', ['category' => $category]);
        }

        return [
            'category' => $category,
            'subcategories' => $category->getChildren(),
        ];
    }

    /**
     * @Template()
     */
    public function productsAction(Request $request, $category)
    {
        $em = $this->getDoctrine()->getManager();
        $r = $em->getRepository('AxSShopBundle:Product');
        $products = $r->findBy([
            'category' => $category,
            'visible' => 1,
            'available' => 1
        ], ['title' => 'asc']);

        return [
            'category' => $category,
            'products' => $products,
        ];
    }

    /**
     * @Route("/search", name="search")
     * @Template()
     */
    public function searchAction(Request $request)
    {
        $q = $request->get('q');

        return [
            'q' => $q,
        ];
    }

    /**
     * @Route("/product/{url}", name="product")
     * @Template()
     */
    public function productAction($url)
    {
        return [];
    }

    /**
     * @Route("/cart", name="cart")
     * @Template()
     */
    public function cartAction()
    {
        return [];
    }
}