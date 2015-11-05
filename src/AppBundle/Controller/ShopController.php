<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 05.11.15
 * Time: 16:36
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ShopController
{
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
     * @Route("/cart", name="cart")
     * @Template()
     */
    public function cartAction()
    {
        return [];
    }
}