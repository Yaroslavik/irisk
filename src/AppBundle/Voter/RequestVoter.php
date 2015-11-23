<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 24.11.15
 * Time: 0:08
 */

namespace AppBundle\Voter;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RequestVoter implements VoterInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Checks whether an item is current.
     *
     * If the voter is not able to determine a result,
     * it should return null to let other voters do the job.
     *
     * @param ItemInterface $item
     * @return boolean|null
     */
    public function matchItem(ItemInterface $item)
    {
        $itemUrl = static::removeFrontController($item->getUri());
        $currentUrl = static::removeFrontController($this->container->get('request')->getRequestUri());

        if ($itemUrl == '/') {
            return $currentUrl == $itemUrl;
        } else {
            return (strpos($currentUrl, $itemUrl) !== false);
        }
    }

    public static function removeFrontController($url)
    {
        return preg_replace('/^[^\.]*\.php/i', '', $url);
    }
}