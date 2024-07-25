<?php

declare(strict_types=1);

namespace App\Module\Auth\Infrastructure\Doctrine\Dbal\Type;

use App\Module\Auth\Domain\DomainModel\Entity\User\UserPassword;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Override;

/**
 * @author Yiimar
 */
final class UserPasswordType extends StringType
{
    public const NAME = 'user_password';

    #[Override]
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof UserPassword ? $value->getValue() : (string)$value;
    }

    /**
     * @throws \App\Module\Auth\Domain\DomainModel\Exception\ValueObject\User\UserPasswordIsEmpty
     */
    #[Override]
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?UserPassword
    {
        return !empty($value) ? UserPassword::create((string)$value) : null;
    }
}