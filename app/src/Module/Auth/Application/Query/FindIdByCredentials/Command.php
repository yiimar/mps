<?php

declare(strict_types=1);

namespace App\Module\Auth\Application\Query\FindIdByCredentials;

final readonly class Command
{
    public function __construct(
        public string $id,
        public bool $isActive,
    ) {}
}
