<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Doctrine\Dbal\Type\Core;

use Exception;

/**
 * @author Yiimar
 */
abstract class AbstractValueIsNotValidException extends Exception implements ValueIsNotValid
{
    public static function create($value): static
    {
        $option = (string) $value;
        $message = static::getErrorMessage($option);
        return new static($message);
    }
}