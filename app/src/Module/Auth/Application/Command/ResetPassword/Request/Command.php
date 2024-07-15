<?php

declare(strict_types=1);

namespace App\Module\Auth\Application\Command\ResetPassword\Request;

final readonly class Command
{
    public function __construct(
        public string $email
    ) {}
}
