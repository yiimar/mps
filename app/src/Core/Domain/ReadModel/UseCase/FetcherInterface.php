<?php

declare(strict_types=1);

namespace App\Core\Domain\ReadModel\UseCase;

/**
 * @author Dmitry S
 */
interface FetcherInterface
{
    public function fetch(FetchCommand $command);
}