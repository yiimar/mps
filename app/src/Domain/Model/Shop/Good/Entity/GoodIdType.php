<?php

declare(strict_types=1);

namespace App\Domain\Model\Shop\Good\Entity;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Override;

/**
 * @author Dmitry S
 */
final class GoodIdType extends StringType
{
    public const NAME = 'good_id';

    #[Override]
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof GoodId ? $value->getValue() : (string)$value;
    }

    #[Override]
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?GoodId
    {
        return !empty($value) ? new GoodId((string)$value) : null;
    }
}