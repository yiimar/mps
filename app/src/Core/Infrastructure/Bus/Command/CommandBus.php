<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Bus\Command;

use Throwable;

interface CommandBus
{
    /** @throws Throwable */
    public function __invoke(object $command): void;
}