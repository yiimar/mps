<?php

declare(strict_types=1);

namespace App\Module\Auth\Application\Query\FindIdentityById;

/**
 * @author Yiimar
 */
final readonly class Query
{
    public function __construct(
        public string $id
    ) {}
}