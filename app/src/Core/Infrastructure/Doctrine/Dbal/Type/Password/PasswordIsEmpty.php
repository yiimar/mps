<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Doctrine\Dbal\Type\Password;

use App\Core\Domain\DomainModel\ValueObject\Exception\AbstractValueIsNotValidException;

/**
 * @author Yiimar
 */
class PasswordIsEmpty extends AbstractValueIsNotValidException
{
    public static function makeMessage(string $option): string
    {
        return 'Незаполненный Пароль: ' . $option;
    }
}