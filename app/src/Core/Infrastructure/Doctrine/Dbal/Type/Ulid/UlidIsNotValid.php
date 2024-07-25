<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Doctrine\Dbal\Type\Ulid;

use App\Core\Domain\DomainModel\ValueObject\Exception\AbstractValueIsNotValidException;

/**
 * @author Yiimar
 */
class UlidIsNotValid extends AbstractValueIsNotValidException
{
    public static function makeMessage(string $option): string
    {
        return 'Невалидный Ulid: ' . $option;
    }
}