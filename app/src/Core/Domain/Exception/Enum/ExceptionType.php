<?php

declare(strict_types=1);


namespace App\Core\Domain\Exception\Enum;

/**
 * ExceptionType enum to define constant values for various exception types.
 *
 * This enum serves as a repository for defining different types of exceptions
 * in a consistent manner across the application. It helps in categorizing and
 * handling exceptions based on their types.
 *
 * @author Dmitry S
 */
enum ExceptionType: string
{
    case EXCEPTION = 'exception';
    case VALIDATION = 'validation';
}
