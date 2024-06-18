<?php

declare(strict_types=1);

namespace App\Core\Application\Validation;

/**
 * These constants are used to specify the type of constraint being applied
 * in validation error messages.
 *
 * @author Dmitry S
 */
enum TypeKey: string
{
    case REQUIRED = 'required';
    case MIN_LENGTH = 'min_length';
    case MAX_LENGTH = 'max_length';
    case FORMAT = 'format';
    case NOT_BLANK = 'not_blank';
    case NOT_NULL = 'not_null';
    case RANGE = 'range';
    case INVALID = 'invalid';
}
