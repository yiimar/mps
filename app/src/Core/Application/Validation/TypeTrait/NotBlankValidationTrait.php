<?php

declare(strict_types=1);

namespace App\Core\Application\Validation\TypeTrait;

use App\Core\Application\Validation\TypeKey;
use App\Core\Application\Validation\ValidationErrorFormatter;

/**
 * @author Dmitry S
 */
trait NotBlankValidationTrait
{
    /**
     * Validates that a string is not blank.
     *
     * @param string|null $value     the string to validate
     * @param string      $fieldName the name of the field for error messaging
     *
     * @return array an array containing a formatted validation error if the string is blank
     */
    public function validateNotBlank(?string $value, string $fieldName): array
    {
        if (null === $value || '' === $value) {
            return ValidationErrorFormatter::format(
                TypeKey::NOT_BLANK,
                $fieldName,
                $value
            );
        }

        return [];
    }
}