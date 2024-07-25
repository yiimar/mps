<?php

declare(strict_types=1);

namespace App\Module\Shop\Order\Order\UI\Http\Web\Controller\Create;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    #[Route('/order/create/', name: 'order_create', methods: ['GET'])]
    public function __invoke(): Response
    {
        return $this->render('@order/create.html.twig', [
            'controller_name' => 'Controller',
            'controller_path' => realpath(__DIR__),
        ]);
    }
}
