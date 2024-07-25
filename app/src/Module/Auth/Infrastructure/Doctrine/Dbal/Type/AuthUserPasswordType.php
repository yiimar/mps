<?php

declare(strict_types=1);

namespace App\Module\Auth\Infrastructure\Doctrine\Dbal\Type;

use App\Module\Auth\Domain\DomainModel\Entity\User\Password;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Override;

/**
 * @author Yiimar
 */
final class AuthUserPasswordType extends StringType
{
    public const NAME = 'auth_user_password';

    #[Override]
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof Password ? $value->getValue() : (string)$value;
    }

    /**
     * @throws \App\Core\Infrastructure\Doctrine\Dbal\Type\Password\PasswordIsNotValid
     */
    #[Override]
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Password
    {
        return !empty($value) ? Password::create((string)$value) : null;
    }
}