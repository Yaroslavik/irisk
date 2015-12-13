<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 13.12.15
 * Time: 17:32
 */

namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{
    public function getName()
    {
        return 'app_extension';
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('digits', array($this, 'digitsFilter')),
        );
    }

    public function digitsFilter($value)
    {
        return preg_replace('/[^0-9]+/i', '', $value);
    }
}