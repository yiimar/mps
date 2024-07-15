<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Doctrine\Dbal\Type\Password;

use App\Core\Infrastructure\Doctrine\Dbal\Type\Core\ValueIsNotValid;
use Exception;

/**
 * @author Yiimar
 */
class PasswordIsNotValid extends Exception implements ValueIsNotValid
{
    public static function create(string $value): static
    {
        return new self('Невалидный Password: ' . $value);
    }
}