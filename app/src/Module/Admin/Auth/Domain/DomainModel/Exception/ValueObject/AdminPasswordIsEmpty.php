<?php

declare(strict_types=1);

namespace App\Module\Admin\Auth\Domain\DomainModel\Exception\ValueObject;

use App\Core\Domain\DomainModel\ValueObject\Exception\InvalidValueObject;
use Exception;

/**
 * @author Yiimar
 */
final class AdminPasswordIsEmpty extends Exception implements InvalidValueObject
{
    public static function create(): self
    {
        return new self('Admin Password provided is empty');
    }
}