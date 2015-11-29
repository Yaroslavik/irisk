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
        if ($path === null) return [];

        $parts = preg_split('/\\//', $path);
        $last = end($parts);

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $r = $em->getRepository('AxSShopBundle:ShopCategory');

        $category = $r->findOneBy([
            'slug' => $last,
            'visible' => 1,
        ]);

        if (!$category) throw new NotFoundHttpException();

        return [
            'category' => $category,
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