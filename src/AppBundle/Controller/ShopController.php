<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 05.11.15
 * Time: 16:36
 */

namespace AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Pagination\SlidingPagination;
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
    public function catalogAction(Request $request, $path = null)
    {
        /** @var EntityManager $em */
        $r = $this->getDoctrine()->getRepository('AxSShopBundle:ShopCategory');

        // If path not specified show root categories
        if (!$path) {
            return [
                'category' => null,
                'subcategories' => $r->findBy([
                    'visible' => 1,
                    'level' => 0,
                ]),
            ];
        }

        // Parse path and find current category
        $parts = preg_split('/\\//', $path);
        $last = end($parts);

        $category = $r->findOneBy([
            'slug' => $last,
            'visible' => 1,
        ]);

        if (!$category) throw new NotFoundHttpException();

        // Contain visible subcategories?
        $containSubcategories = false;
        foreach ($category->getChildren() as $subcategory) {
            if ($subcategory->getVisible()) {
                $containSubcategories = true;
                break;
            }
        }

        // If not contains subcategories forward to products page
        if (!$containSubcategories) {
            return $this->forward('AppBundle:Shop:products', [
                'category' => $category,
                'request' => $request,
            ]);
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
        $page = $request->query->get('page', 1);
        $orderBy = $request->query->get('sort', 'title-asc');

        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder()
            ->select('p')
            ->from('AxSShopBundle:Product', 'p')
            ->where('p.visible = :visible')
            ->andWhere('p.available = :available')
            ->andWhere('p.category = :category')
            ->setParameter(':visible', 1)
            ->setParameter(':available', 1)
            ->setParameter(':category', $category);

        switch ($orderBy) {
            case 'title-asc':
                $qb->orderBy('p.title', 'asc');
                break;
            case 'title-desc':
                $qb->orderBy('p.title', 'desc');
                break;
            case 'price-asc':
                $qb->orderBy('p.cost', 'asc');
                break;
            case 'price-desc':
                $qb->orderBy('p.cost', 'desc');
                break;
        }

        /** @var SlidingPagination $pagination */
        $pagination = $this->get('knp_paginator')->paginate(
            $qb,
            $page,
            200
        );

        return [
            'category' => $category,
            'pagination' => $pagination,
        ];
    }

    /**
     * @Route("/special-offers", name="special")
     * @Template()
     */
    public function specialAction()
    {
        $products = $this->getDoctrine()->getRepository('AxSShopBundle:Product')
            ->findBy([
                'visible' => 1,
                'available' => 1,
                'featured' => 1,
            ], ['updatedAt' => 'DESC']);

        return [
            'products' => $products,
        ];
    }

    /**
     * @Route("/search", name="search")
     * @Template()
     */
    public function searchAction(Request $request)
    {
        return $this->forward('AppBundle:Default:development');

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
        $r = $this->getDoctrine()->getRepository('AxSShopBundle:Product');
        $product = $r->findOneBy([
            'slug' => $url,
            'visible' => 1,
        ]);

        if (!$product) throw new NotFoundHttpException();

        return [
            'product' => $product,
        ];
    }

    /**
     * @Route("/cart", name="cart")
     * @Template()
     */
    public function cartAction()
    {
        return $this->forward('AppBundle:Default:development');

        return [];
    }
}