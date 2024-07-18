<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Doctrine\Dbal\Type\Collection;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;
use Webmozart\Assert\Assert;
use function call_user_func;

/**
 * @author Yiimar
 */
abstract class AbstractCollectionType extends Type
{
    public const NAME = 'collection';

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $this->getInheritedType()->getSQLDeclaration($column, $platform);
    }

    public static function getSupportedValueObjectType(): string
    {
        return BaseCollection::class;
    }

    /**
     * @inheritdoc
     * @throws \Doctrine\DBAL\Exception
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        Assert::isInstanceOf($value, BaseCollection::class);
        /** @var BaseCollection $value */

        return $this->getInheritedType()->convertToDatabaseValue($value->getValue(), $platform);
    }

    /**
     * @inheritdoc
     * @throws \Doctrine\DBAL\Exception
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?BaseCollection
    {
        $value = $this->getInheritedType()->convertToPHPValue($value, $platform);

        if ($value === null) {
            return null;
        }

        Assert::isArray($value);

        /** @var BaseCollection $collection */
        $collection = call_user_func([BaseCollection::class, 'fromValue'], $value);
        Assert::isInstanceOf($collection, BaseCollection::class);

        return $collection;
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    private function getInheritedType(): JsonType
    {
        /** @var JsonType $type */
        $type = self::getType(Types::JSON);

        return $type;
    }

    public function getName(): string
    {
        return static::NAME;
    }
}