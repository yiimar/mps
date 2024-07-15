<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Doctrine\Dbal\Type\Email;

use App\Core\Infrastructure\Doctrine\Dbal\Type\Core\AbstractValueIsNotValidException;

/**
 * @author Yiimar
 */
class EmailIsNotValid extends AbstractValueIsNotValidException
{
    public static function getErrorMessage(string $option): string
    {
        return 'Невалидный Email: ' . $option;
    }
}