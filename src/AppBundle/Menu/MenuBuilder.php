<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 19.11.15
 * Time: 16:56
 */

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;

class MenuBuilder
{
    protected $factory;

    /**
     * @param FactoryInterface $factory
     *
     * Add any other dependency you need
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createMainMenu()
    {
        $menu = $this->factory->createItem('menu');

        $menu->addChild('Главная', array('route' => 'homepage'));
        $menu->addChild('О магазине', array('route' => 'page', 'routeParameters' => ['slug' => 'about']));
        $menu->addChild('Каталог товаров', array('route' => 'catalog'));
        $menu->addChild('Доставка и оплата', array('route' => 'page-delivery'));
        $menu->addChild('Полезные статьи', array('route' => 'articles'));
        $menu->addChild('Дилерам', array('route' => 'page', 'routeParameters' => ['slug' => 'for-dealers']));
        $menu->addChild('Корзина', array('route' => 'cart'));

        foreach ($menu->getChildren() as $item) {
            $item->setLinkAttribute('class', 'btn btn-warning');
        }

        return $menu;
    }
}