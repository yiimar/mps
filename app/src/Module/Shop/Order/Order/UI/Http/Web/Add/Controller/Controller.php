<?php

declare(strict_types=1);

namespace App\Module\Shop\Order\Order\UI\Http\Web\Add\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    #[Route('/order/add', name: 'order_add', methods: ['GET'])]
    public function __invoke(): Response
    {
        return $this->render('@order_add/html.twig', [
            'controller_name' => 'Controller',
            'controller_path' => realpath(__DIR__),
        ]);
    }
}
