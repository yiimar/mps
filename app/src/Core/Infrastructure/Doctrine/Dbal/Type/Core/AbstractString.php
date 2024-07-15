<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Doctrine\Dbal\Type\Core;

/**
 * @author Yiimar
 */
abstract class AbstractString extends AbstractScalar
{
    public static function validate($value): void
    {
        if (!is_string($value)) {
            $e = static::$exception;
            $e::create($value);
        }
    }
}