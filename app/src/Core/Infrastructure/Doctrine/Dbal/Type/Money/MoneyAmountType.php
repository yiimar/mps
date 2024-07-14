<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Doctrine\Dbal\Type\Money;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\IntegerType;
use function is_int;

class MoneyAmountType extends IntegerType
{
    public const NAME = 'money_amount';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): null | int
    {
        return $value instanceof BaseMoneyAmount ? $value->getValue() : (int)$value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?BaseMoneyAmount
    {
        return is_int($value) ? new BaseMoneyAmount($value) : null;
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
