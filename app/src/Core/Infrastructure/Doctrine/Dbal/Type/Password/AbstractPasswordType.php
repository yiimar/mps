<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Doctrine\Dbal\Type\Password;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\StringType;

/**
 * @author Yiimar
 */
abstract class AbstractPasswordType extends StringType
{
    public const NAME = 'password';

    public function getName(): string
    {
        return static::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): null | string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof BasePassword) {
            return parent::convertToDatabaseValue($value->toString(), $platform);
        }

        throw ConversionException::conversionFailedInvalidType(
            $value,
            $this->getName(),
            ['null', BasePassword::class],
        );
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?BasePassword
    {
        /** @var string|null $value */
        $value = parent::convertToPHPValue($value, $platform);

        if ($value === null) {
            return null;
        }

        try {
            return BasePassword::create($value);
        } catch (PasswordIsNotValid $e) {
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