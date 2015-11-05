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
}
