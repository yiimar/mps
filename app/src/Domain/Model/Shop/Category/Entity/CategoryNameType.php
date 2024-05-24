<?php

declare(strict_types=1);

namespace App\Domain\Model\Shop\Category\Entity;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Override;

/**
 * @author Dmitry S
 */
final class CategoryNameType extends StringType
{
    public const NAME = 'category_name';

    #[Override]
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof CategoryName ? $value->getValue() : (string)$value;
    }

    #[Override]
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?CategoryName
    {
        return !empty($value) ? new CategoryName((string)$value) : null;
    }
}