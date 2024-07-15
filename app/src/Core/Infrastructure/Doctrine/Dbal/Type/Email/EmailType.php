<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Doctrine\Dbal\Type\Email;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\StringType;

/**
 * @author Yiimar
 */
class EmailType extends StringType
{
    public const NAME = 'email';

    public function getName(): string
    {
        return static::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): null | string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof BaseEmail) {
            return parent::convertToDatabaseValue($value->toString(), $platform);
        }

        throw ConversionException::conversionFailedInvalidType(
            $value,
            $this->getName(),
            ['null', BaseEmail::class],
        );
    }

    /**
     * @param $value
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform
     * @return \App\Core\Infrastructure\Doctrine\Dbal\Type\Email\BaseEmail|null
     * @throws \Doctrine\DBAL\Types\ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?BaseEmail
    {
        /** @var string|null $value */
        $value = parent::convertToPHPValue($value, $platform);

        if ($value === null) {
            return null;
        }

        try {
            return BaseEmail::create($value);
        } catch (EmailIsNotValid $e) {
            throw ConversionException::conversionFailed(
                $value,
                $this->getName(),
                $e,
            );
        }
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getColumnDeclarationSQL('varchar', $column);
    }
}