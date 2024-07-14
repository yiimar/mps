<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Doctrine\Dbal\Type\Password;

use Exception;

/**
 * @author Dmitry S
 */
class PasswordIsNotValid extends Exception
{
    public static function create(string $password): self
    {
        return new self('Невалидный Password: ' . $password);
    }
}