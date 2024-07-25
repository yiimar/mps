<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Doctrine\Dbal\Type\Email;

use App\Core\Domain\DomainModel\ValueObject\Exception\AbstractValueIsNotValidException;

/**
 * @author Yiimar
 */
class EmailIsNotValid extends AbstractValueIsNotValidException
{
    public static function makeMessage(string $option): string
    {
        return 'Невалидный Email: ' . $option;
    }
}