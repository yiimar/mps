<?php

declare(strict_types=1);

namespace App\Module\Home\UI\Http\Web\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    #[Route('/', name: 'home', methods: ['GET'])]
    public function __invoke(): Response
    {
        return $this->render('@home/html.twig', [
            'controller_name' => 'Controller',
            'controller_path' => realpath(__DIR__),
        ]);
    }
}
