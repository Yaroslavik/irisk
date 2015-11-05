<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Route("/page/{url}", name="page")
     * @Template()
     */
    public function pageAction($url)
    {
        return [];
    }

    /**
     * @Route("/catalog", name="catalog")
     * @Template()
     */
    public function catalogAction()
    {
        return [];
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
     * @Route("/delivery", name="page-delivery")
     * @Template()
     */
    public function pageDeliveryAction()
    {
        return [];
    }


    /**
     * @Route("/articles", name="articles")
     * @Template()
     */
    public function articlesAction()
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
