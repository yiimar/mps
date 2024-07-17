<?php

declare(strict_types=1);

namespace App\Module\Admin\UI\Http\Web\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    #[Route('/admin/', name: 'admin', methods: ['GET'])]
    public function __invoke(): Response
    {
        return $this->render('@admin/html.twig', [
            'controller_name' => 'Controller',
            'controller_path' => realpath(__DIR__),
        ]);
    }
}
