<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Bus\Query;

use Throwable;

interface QueryBus
{
    /** @throws Throwable */
    public function __invoke(object $query): void;
}