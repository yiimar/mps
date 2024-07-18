<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Doctrine\Dbal\Type\Money;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use function is_string;

class AbstractMoneyCurrencyType extends StringType
{
    public const NAME = 'money_currency';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): null | string
    {
        return $value instanceof BaseMoneyCurrency ? $value->getValue() : (string)$value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?BaseMoneyCurrency
    {
        return is_string($value) ? new BaseMoneyCurrency($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
