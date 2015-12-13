<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 13.12.15
 * Time: 18:08
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ComponentController extends Controller
{
    /**
     * @Template()
     */
    public function galleryAction()
    {
        $r = $this->getDoctrine()->getRepository('AppBundle:Slide');
        $slides = $r->getVisibleItems();
        return [
            'slides' => $slides,
        ];
    }
}