<?php

declare(strict_types=1);

namespace App\Module\Auth\Application\Command\ResetPassword\Reset;

final readonly class Command
{
    public function __construct(
        public string $token,
        public string $password
    ) {}
}
