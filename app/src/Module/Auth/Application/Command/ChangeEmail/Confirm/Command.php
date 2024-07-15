<?php

declare(strict_types=1);

namespace App\Module\Auth\Application\Command\ChangeEmail\Confirm;

final readonly class Command
{
    public function __construct(
        public string $token
    ) {}
}
