<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 19.11.15
 * Time: 16:56
 */

namespace AppBundle\Menu;

use AxS\ShopBundle\Entity\ShopCategory;
use Doctrine\ORM\EntityManager;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class MenuBuilder
{
    protected $factory;

    protected $em;

    /**
     * @param FactoryInterface $factory
     * @param EntityManager $em
     *
     * Add any other dependency you need
     */
    public function __construct(FactoryInterface $factory, EntityManager $em)
    {
        $this->factory = $factory;
        $this->em = $em;
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

    public function createCatalogMenu()
    {
        $r = $this->em->getRepository('AxSShopBundle:ShopCategory');
        $roots = $r->getRootNodes('order', 'asc');

        $menu = $this->factory->createItem('catalog_menu');
        $this->buildCatalogMenu($menu, $roots);
        return $menu;
    }

    /**
     * @param ItemInterface $menuItem
     * @param ShopCategory[]|\Traversable $categories
     * @param string $path
     * @return mixed
     */
    protected function buildCatalogMenu(ItemInterface $menuItem, $categories, $path = '')
    {
        foreach ($categories as $category) {
            if ($path !== '') $path .= '/';
            $path .= $category->getSlug();

            $item = $menuItem->addChild(
                $category->getTitle(),
                ['route' => 'catalog', 'routeParameters' => [
                    'path' => $path
                ]]
            );

            $this->buildCatalogMenu($item, $category->getChildren(), $path);
        }

        return $menuItem;
    }
}