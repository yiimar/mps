<?php

declare(strict_types=1);

namespace App\Shop\Entity\Category\Entity\Good\Entity;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Override;

/**
 * @author Dmitry S
 */
final class GoodNameType extends StringType
{
    public const NAME = 'good_name';

    #[Override]
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof GoodName ? $value->getValue() : (string)$value;
    }

    #[Override]
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?GoodName
    {
        return !empty($value) ? new GoodName((string)$value) : null;
    }
}