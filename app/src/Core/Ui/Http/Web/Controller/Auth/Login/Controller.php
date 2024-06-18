<?php

declare(strict_types=1);

namespace App\Core\Ui\Http\web\Controller\Auth\Login;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @author Dmitry S
 */
class Controller extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ['GET'])]
    public function __invoke(): Response
    {
        return $this->render('auth/login/html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }
}