<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony7;

use App\Infrastructure\Http\Message\Request\ServerRequest;
use App\Infrastructure\Http\Message\Response\ServerResponse;

/**
 * @author Dmitry S
 */
interface TerminableInterface
{
    /**
     * Terminates a request/response cycle.
     *
     * Should be called after sending the response and before shutting down the kernel.
     */
    public function terminate(ServerRequest $request, ServerResponse $response): void;
}