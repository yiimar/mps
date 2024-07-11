<?php

declare(strict_types=1);

namespace App\Module\Auth\UI\Http\Web\Controller\Logout;

use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class Controller extends AbstractController
{
    #[Route(path: '/logout', name: 'app_logout')]
    public function __invoke(): Response
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
