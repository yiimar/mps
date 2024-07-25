<?php

declare(strict_types=1);

namespace App\Module\Admin\Auth\UI\Http\Web\Controller\Login;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class Controller extends AbstractController
{
    #[Route(path: '/admin/login', name: 'admin_login')]
    public function __invoke(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@admin_auth/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
}
