<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Bus\Query;

use League\Tactician\CommandBus as LeagueTacticianQueryBus;

final readonly class TacticianQueryBus implements QueryBus
{
    public function __construct(
        private LeagueTacticianQueryBus $queryBus,
    ) {
    }

    public function __invoke(object $query): void
    {
        $this->queryBus->handle($query);
    }
}