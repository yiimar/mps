<?php

declare(strict_types=1);

namespace App\Module\Auth\Domain\DomainModel\Exception\ValueObject\User;

use App\Core\Domain\DomainModel\ValueObject\Exception\InvalidValueObject;
use Exception;

/**
 * @author Yiimar
 */
final class UserPasswordIsEmpty extends Exception implements InvalidValueObject
{
    public static function create(): self
    {
        return new self('User Password provided is empty');
    }
}