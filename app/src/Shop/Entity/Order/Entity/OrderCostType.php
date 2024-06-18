<?php

declare(strict_types=1);

namespace App\Shop\Entity\Order\Entity;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\IntegerType;
use Override;

/**
 * @author Dmitry S
 */
class OrderCostType extends IntegerType
{
    public const NAME = 'order_cost';

    #[Override]
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?int
    {
        return $value instanceof OrderCost ? $value->getValue() : (int)$value;
    }

    #[Override]
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?OrderCost
    {
        return !empty($value) ? new OrderCost((int)$value) : null;
    }
}