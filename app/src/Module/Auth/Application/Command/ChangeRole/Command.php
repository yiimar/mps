<?php

declare(strict_types=1);

namespace App\Module\Auth\Application\Command\ChangeRole;

final readonly class Command
{
    public function __construct(
        public string $id,
        public string $role
    ) {}
}
