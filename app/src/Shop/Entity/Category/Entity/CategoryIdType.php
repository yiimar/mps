<?php

declare(strict_types=1);

namespace App\Shop\Entity\Category\Entity;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Override;

/**
 * @author Dmitry S
 */
final class CategoryIdType extends StringType
{
    public const NAME = 'category_id';

    #[Override]
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof CategoryId ? $value->getValue() : (string)$value;
    }

    #[Override]
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?CategoryId
    {
        return !empty($value) ? new CategoryId((string)$value) : null;
    }
}