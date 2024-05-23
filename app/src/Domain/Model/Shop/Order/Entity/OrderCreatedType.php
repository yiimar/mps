<?php

declare(strict_types=1);


namespace App\Domain\Model\Shop\Order\Entity;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DateTimeImmutableType;
use Override;

/**
 * @author Dmitry S
 */
class OrderCreatedType extends DateTimeImmutableType
{
    public const NAME = 'order_created';

    #[Override]
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof OrderCreated ? $value->getValue()->format('Y-m-d H:i:s') : (string)$value;
    }

    #[Override]
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?OrderCreated
    {
        return !empty($value) ? new OrderCreated($value) : null;
    }}