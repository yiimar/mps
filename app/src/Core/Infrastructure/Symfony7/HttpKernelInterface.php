<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Symfony7;

use App\Infrastructure\Symfony7\Request;
use App\Infrastructure\Symfony7\Response;

/**
 * @author Dmitry S
 */
interface HttpKernelInterface
{
    /**
     * Handles a Request to convert it to a Response.
     */
    public function handle(Request $request): Response;
}