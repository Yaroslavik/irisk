<?php

namespace AppBundle\Controller;

use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Pagination\SlidingPagination;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        $products = $this->getDoctrine()->getRepository('AxSShopBundle:Product')
            ->findBy([
                'visible' => 1,
                'available' => 1,
                'featured' => 1,
            ], ['updatedAt' => 'DESC'], 8);

        $categories = $this->getDoctrine()->getRepository('AxSShopBundle:ShopCategory')
            ->findBy([
                'visible' => 1,
                'featured' => 1,
            ], ['order' => 'ASC'], 12);

        return [
            'products' => $products,
            'categories' => $categories,
        ];
    }

    /**
     * @Route("/page/{slug}", name="page")
     * @Template()
     */
    public function pageAction($slug)
    {
        $page = $this->getDoctrine()
            ->getRepository('AxSPageBundle:Page')
            ->findOneBy(['visible' => 1, 'slug' => $slug]);

        if (!$page) throw new NotFoundHttpException();

        return [
            'page' => $page
        ];
    }

    /**
     * @Route("/delivery", name="page-delivery")
     * @Template()
     */
    public function pageDeliveryAction()
    {
        return [];
    }

    /**
     * @Route("/articles/{page}", requirements={"page": "\d+"}, defaults={"page"=1}, name="articles")
     * @Template()
     */
    public function articlesAction($page)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder()
            ->select('a')
            ->from('AxSArticleBundle:Article', 'a')
            ->where('a.visible = :visible')
            ->orderBy('a.createdAt', 'DESC')
            ->setParameter(':visible', 1);

        /** @var SlidingPagination $pagination */
        $pagination = $this->get('knp_paginator')->paginate(
            $qb,
            $page,
            5
        );

        if (!count($pagination)) throw new NotFoundHttpException();

        return [
            'pagination' => $pagination,
        ];
    }

    /**
     * @Route("/articles/{slug}", name="article")
     * @Template()
     */
    public function articleAction($slug)
    {
        $article = $this->getDoctrine()
            ->getRepository('AxSArticleBundle:Article')
            ->findOneBy(['visible' => 1, 'slug' => $slug]);

        if (!$article) throw new NotFoundHttpException();

        return [
            'article' => $article,
        ];
    }
}
