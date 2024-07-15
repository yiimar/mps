<?php

declare(strict_types=1);

namespace App\Module\Auth\Application\Query\FindIdByCredentials;

final readonly class Query
{
    public function __construct(
        public string $email = '',
        public string $password = ''
    )
    {}
}
