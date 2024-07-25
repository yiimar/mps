<?php

declare(strict_types=1);

namespace App\Core\Domain\DomainModel\ValueObject\Core;

/**
 * @author Yiimar
 */
interface ValueObjectInterface
{
    public static function create(mixed $value): mixed;

    public function getValue(): mixed;

    public static function defaultNamedConstructor(): callable;

    public function toString(): string;
}