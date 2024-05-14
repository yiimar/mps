<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony7;

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