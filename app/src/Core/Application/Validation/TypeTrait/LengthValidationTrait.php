<?php

declare(strict_types=1);

namespace App\Core\Application\Validation\TypeTrait;

use App\Core\Application\Validation\TypeKey;
use App\Core\Application\Validation\ValidationErrorFormatter;

/**
 * @author Dmitry S
 */
trait LengthValidationTrait
{
    /**
     * Validates the length of a string.
     * Checks if the given string's length falls within the specified range. If the string length is outside
     * the range, a formatted validation error message is returned.
     *
     * @param string $value     the string to validate
     * @param int    $min       the minimum length
     * @param int    $max       the maximum length
     * @param string $fieldName the name of the field being validated, used in the error message
     *
     * @return array an array containing the validation error message, if any
     */
    public function validateLength(string $value, int $min, int $max, string $fieldName): array
    {
        $length = strlen($value);

        if ($length < $min) {
            $constraint = TypeKey::MIN_LENGTH;
        } elseif ($length > $max) {
            $constraint = TypeKey::MAX_LENGTH;
        } else {
            return [];
        }

        return ValidationErrorFormatter::format(
            $constraint,
            $fieldName,
            $value
        );
    }
}