<?php

declare(strict_types=1);


namespace App\Core\Domain\Exception\Enum;

/**
 * ExceptionMessage enum to hold constant messages for various exceptions.
 *
 * This enum provides a centralized location for defining standard messages
 * associated with different types of exceptions. Using these constants ensures
 * consistency across the application when handling and reporting errors.
 *
 * @author Dmitry S
 */
enum ExceptionMessage: string
{
    case INTERNAL = 'internal_error';
    case VALIDATION = 'validation';
    case NOT_FOUND = 'not_found';
    case DUPLICATE = 'duplicate';
    case NOT_SUPPORTED = 'not_supported';
    case INVALID_PAYLOAD = 'invalid_payload';
}