<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(): Response
    {
        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }
}
// config/packages/security.php
use Symfony\Config\SecurityConfig;

return static function (SecurityConfig $security) {
    // ...

    $mainFirewall = $security->firewall('main');

    // "login" is the name of the route created previously
    $mainFirewall->formLogin()
        ->loginPath('login')
        ->checkPath('login')
    ;
};





