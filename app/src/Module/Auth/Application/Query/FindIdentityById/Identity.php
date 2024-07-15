<?php

declare(strict_types=1);

namespace App\Module\Auth\Application\Query\FindIdentityById;

final readonly class Identity
{
    public function __construct(
        public string $id,
        public string $role
    ) {}
}
