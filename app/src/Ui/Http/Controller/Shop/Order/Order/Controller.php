<?php

declare(strict_types=1);

namespace App\Ui\Http\Controller\Shop\Order\Order;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @author Dmitry S
 */
class Controller extends AbstractController
{
    #[Route('/order', name: 'app_order', methods: ['GET'])]
    public function __invoke(): Response
    {
        return $this->render('home/html.twig', [
            'controller_name' => 'OrderController',
        ]);
    }
}