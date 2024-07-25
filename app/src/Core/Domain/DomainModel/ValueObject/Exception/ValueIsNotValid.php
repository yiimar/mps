<?php

declare(strict_types=1);

namespace App\Core\Domain\DomainModel\ValueObject\Exception;

/**
 * @author Yiimar
 */
interface ValueIsNotValid
{
    public static function create(mixed $value): static;
}