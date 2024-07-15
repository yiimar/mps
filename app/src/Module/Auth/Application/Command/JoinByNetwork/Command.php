<?php

declare(strict_types=1);

namespace App\Module\Auth\Application\Command\JoinByNetwork;

final class Command
{
    public function __construct(
        public string $email,
        public string $network,
        public string $identity
    ) {}
}
