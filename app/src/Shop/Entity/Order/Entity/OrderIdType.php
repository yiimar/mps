<?php

declare(strict_types=1);

namespace App\Shop\Entity\Order\Entity;

use App\Core\Infrastructure\Doctrine\Dbal\Type\Ulid\AbstractUlidType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Override;

/**
 * @author Dmitry S
 */
final class OrderIdType extends AbstractUlidType
{
    public const NAME = 'order_id';

    #[Override]
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof OrderId ? $value->getValue() : (string)$value;
    }

    #[Override]
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?OrderId
    {
        return !empty($value) ? new OrderId((string)$value) : null;
    }
}