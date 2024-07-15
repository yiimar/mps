<?php

declare(strict_types=1);

namespace App\Core\Domain\ReadModel\UseCase;

/**
 * @author Yiimar
 */
interface FetcherInterface
{
    public function fetch(FetchCommand $command);
}