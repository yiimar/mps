<?php

declare(strict_types=1);

namespace App\Home\UI\Http\Web\Controller\Home;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @author Dmitry S
 */
class Controller extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET', 'HEAD'])]
    public function __invoke(): Response
    {
        return $this->render('@home/home/html.twig', [
            'controller_name' => 'HomeController',
            'controller_path' => self::class,
        ]);
    }
}