<?php
/**
 * Created by PhpStorm.
 * User: alexsholk
 * Date: 18.12.15
 * Time: 15:46
 */

namespace AppBundle\Misc;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class AuthenticationHandler implements AuthenticationSuccessHandlerInterface
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $url = $this->container->get('router')->generate('sonata_admin_dashboard');
        return new RedirectResponse($url);
    }
}