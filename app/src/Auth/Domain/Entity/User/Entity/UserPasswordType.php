<?php

declare(strict_types=1);

namespace App\Auth\Domain\Entity\User\Entity;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Override;

/**
 * @author Dmitry S
 */
final class UserPasswordType extends StringType
{
    public const NAME = 'user_password';

    #[Override]
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof UserPassword ? $value->getValue() : (string)$value;
    }

    #[Override]
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?UserPassword
    {
        return !empty($value) ? new UserPassword((string)$value) : null;
    }
}