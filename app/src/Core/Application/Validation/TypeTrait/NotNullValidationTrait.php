<?php

declare(strict_types=1);


namespace App\Core\Application\Validation\TypeTrait;

use App\Core\Application\Validation\TypeKey;
use App\Core\Application\Validation\ValidationErrorFormatter;

/**
 * @author Dmitry S
 */
trait NotNullValidationTrait
{
    /**
     * Validates that a value is not null.
     *
     * @param mixed  $value     the value to validate
     * @param string $fieldName the name of the field for error messaging
     *
     * @return array an array containing a formatted validation error if the value is null
     */
    public function validateNotNull(mixed $value, string $fieldName): array
    {
        if (is_null($value)) {
            return ValidationErrorFormatter::format(
                TypeKey::NOT_NULL,
                $fieldName,
                $value
            );
        }

        return [];
    }
}