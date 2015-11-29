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
use Knp\Menu\Matcher\Voter\UriVoter;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

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
        $em = $this->container->get('doctrine')->getManager();
        $r = $em->getRepository('AxSShopBundle:ShopCategory');
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
            $tempPath = $path;
            if ($tempPath !== '') $tempPath .= '/';
            $tempPath .= $category->getSlug();

            $item = $menuItem->addChild(
                $category->getTitle(),
                ['route' => 'catalog', 'routeParameters' => [
                    'path' => $tempPath
                ]]
            );

            $this->buildCatalogMenu(
                $item,
                $category->getChildren(),
                $tempPath
            );
        }

        return $menuItem;
    }

    public function createBreadcrumbsMenu()
    {
        $catalogMenu = $this->createCatalogMenu();
        $result = $this->getCurrentMenuChain($catalogMenu);
        $menu = $this->factory->createItem('breadcrumbs', [
            'childrenAttributes' => [
                'class' => 'breadcrumb',
            ]
        ]);

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