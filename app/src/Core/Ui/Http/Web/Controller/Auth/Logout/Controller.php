<?php

declare(strict_types=1);

namespace App\Core\Ui\Http\web\Controller\Auth\Logout;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @author Dmitry S
 */
class Controller extends AbstractController
{
    #[Route('/logout', name: 'app_logout', methods: ['GET', 'POST'])]
    public function __invoke(): Response
    {
        return $this->render('home/auth/logout/html.twig', [
            'controller_name' => 'LogoutController',
        ]);
    }
}