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
    public function catalogAction(Request $request, $path = null)
    {
        return $this->forward('AppBundle:Default:development');

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
     * @Template()
     */
    public function productsAction(Request $request, $category)
    {
        return $this->forward('AppBundle:Default:development');

        $page = $request->query->get('page', 1);
        $orderBy = $request->query->get('sort', 'title-asc');

        $em = $this->getDoctrine()->getManager();
        $r = $em->getRepository('AxSShopBundle:Product');
        $products = $r->findBy([
            'category' => $category,
            'visible' => 1,
            'available' => 1
        ], ['title' => 'asc']);

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
            case 'title-desc':
                $qb->orderBy('p.cost', 'desc');
                break;
        }

        /** @var SlidingPagination $pagination */
        $pagination = $this->get('knp_paginator')->paginate(
            $qb,
            $page,
            2
        );

        return [
            'category' => $category,
            'products' => $products,
            'pagination' => $pagination,
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
        return $this->forward('AppBundle:Default:development');

        return [];
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