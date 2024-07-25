<?php

declare(strict_types=1);

namespace App\Core\Domain\DomainModel\ValueObject\Exception;

use Exception;

/**
 * @author Yiimar
 * @method static makeMessage(string $option)
 */
abstract class AbstractValueIsNotValidException extends Exception implements ValueIsNotValid
{
    public static function create($value): static
    {
        $option = (string) $value;
        $message = static::makeMessage($option);
        return new static($message);
    }
}