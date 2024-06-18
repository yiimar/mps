<?php

declare(strict_types=1);

namespace App\Core\Application\Validation;

/**
 * @author Dmitry S
 */
final class ValidationErrorFormatter
{
    public const CONSTRAINT_KEY = 'constraint';
    public const FIELD_KEY = 'field';
    public const VALUE_KEY = 'value';

    /**
     * Formats a validation error.
     *
     * @return array the formatted validation error
     */
    public static function format(
        ?TypeKey $constraintKey,
        string  $field,
        mixed   $value
    ): array
    {
        return [
            self::CONSTRAINT_KEY => $constraintKey->value ?? 'undefined',
            self::FIELD_KEY => $field,
            self::VALUE_KEY => $value,
        ];
    }
}