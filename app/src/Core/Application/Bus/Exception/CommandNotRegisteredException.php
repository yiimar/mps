<?php

declare(strict_types=1);

namespace App\Core\Application\Bus\Exception;

use App\Core\Domain\Exception\ApiException;

/**
 * The CommandNotRegisteredException class extends the ApiException class.
 * It represents a specific type of API exception that is thrown when a command is not registered in the system.
 * It has a status code of 409, which represents a conflict error in HTTP.
 * The error message is set to ExceptionMessage::DUPLICATE.
 */
final class CommandNotRegisteredException extends ApiException
{
    public const COMMAND_NOT_REGISTERED = 'command_handler_not_registered';
    public const STATUS_CODE = 409;

    /**
     * The constructor initializes the CommandNotRegisteredException with a status code and an error message.
     * The status code is set to self::STATUS_CODE, and the error message is set to ExceptionMessage::DUPLICATE.
     */
    public function __construct()
    {
        parent::__construct(self::COMMAND_NOT_REGISTERED, self::STATUS_CODE);
    }
}