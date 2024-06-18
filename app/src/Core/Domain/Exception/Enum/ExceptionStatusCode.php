<?php

declare(strict_types=1);


namespace App\Core\Domain\Exception\Enum;

/**
 * This class defines constants for commonly used HTTP status codes in the context of exceptions.
 * These status codes can be associated with different types of exceptions to indicate the nature of the error.
 * 
 * @author Dmitry S
 */
enum ExceptionStatusCode: int
{
    case INTERNAL_ERROR = 500;

    case VALIDATION_ERROR = 422;

    case NOT_FOUND = 404;

    case DUPLICATE = 409;

    case NOT_SUPPORTED = 405;

    case BAD_REQUEST = 400;
}
