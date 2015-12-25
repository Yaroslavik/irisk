<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 19.11.15
 * Time: 16:56
 */

namespace AppBundle\Menu;

use AxS\ShopBundle\Entity\ShopCategory;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Knp\Menu\MenuItem;

class MenuBuilder
{
    protected $factory;
    protected $container;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory, ContainerInterface $container)
    {
        $this->factory = $factory;
        $this->container = $container;
    }

    public function createMainMenu()
    {
        $menu = $this->factory->createItem('menu');

        $menu->addChild('Главная', array('route' => 'homepage'));
        $menu->addChild('Доставка и оплата', array('route' => 'page-delivery'));
        $menu->addChild('Каталог товаров', array('route' => 'catalog'));
        $menu->addChild('Полезные статьи', array('route' => 'articles'));
        $menu->addChild('О магазине', array('route' => 'page', 'routeParameters' => ['slug' => 'about']));
        $menu->addChild('Спецпредложения', array('route' => 'special'));
        $menu->addChild('Оптовые закупки', array('route' => 'page', 'routeParameters' => ['slug' => 'for-dealers']));
        $menu->addChild('Корзина', array('route' => 'cart'));

        return $menu;
    }

    public function createFooterMenu()
    {
        $menu = $this->factory->createItem('menu');

        $menu->addChild('Доставка и оплата', array('route' => 'page-delivery'));
        $menu->addChild('Каталог товаров', array('route' => 'catalog'));
        $menu->addChild('Полезные статьи', array('route' => 'articles'));
        $menu->addChild('О магазине', array('route' => 'page', 'routeParameters' => ['slug' => 'about']));
        $menu->addChild('Оптовые закупки', array('route' => 'page', 'routeParameters' => ['slug' => 'for-dealers']));

        return $menu;
    }

    public function createCatalogMenu()
    {
        $em = $this->container->get('doctrine')->getManager();
        $r = $em->getRepository('AxSShopBundle:ShopCategory');
        $roots = $r->getRootNodes('order', 'asc');

        $menu = $this->factory->createItem('catalog_menu', [
            'childrenAttributes' => [
                'class' => 'topnav',
            ]
        ]);

        $this->buildCatalogMenu($menu, $roots);
        return $menu;
    }

    /**
     * @param ItemInterface $menuItem
     * @param ShopCategory[]|\Traversable $categories
     * @return mixed
     */
    protected function buildCatalogMenu(ItemInterface $menuItem, $categories)
    {
        foreach ($categories as $category) {
            $params = [
                'route' => 'catalog',
                'routeParameters' => ['path' => $category->getSlugPath()],
            ];

            $item = $menuItem->addChild($category->getTitle(), $params);

            if ($category->getChildren()->count()) {
                $this->buildCatalogMenu($item, $category->getChildren());
            }
        }

        return $menuItem;
    }

    public function createBreadcrumbsMenu()
    {
        $catalogMenu = $this->createCatalogMenu();
        $result = $this->getCurrentMenuChain($catalogMenu);

        $menu = $this->factory->createItem('breadcrumbs');
        $menu->addChild('Каталог', ['route' => 'catalog']);

        foreach ($result as $i => $item) {
            /** @var ItemInterface $item */
            $menu->addChild($item->getLabel(), ['uri' => $item->getUri()]);
        }

        return $menu;
    }

    protected function getCurrentMenuChain($menu)
    {
        $chain = [];
        $voter = $this->container->get('app.voter.request');

        while (count($menu)) {
            foreach ($menu as $item) {
                if ($voter->matchItem($item)) {
                    /** @var MenuItem $item */
                    $chain[] = $item;
                    $menu = $item;
                    continue 2;
                }
            }

            break;
        }

        return $chain;
    }
}