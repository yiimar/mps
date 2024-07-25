<?php

declare(strict_types=1);

namespace App\Module\Auth\Infrastructure\Doctrine\Dbal\Type;

use App\Module\Auth\Domain\DomainModel\Entity\User\UserEmail;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Override;

final class UserEmailType extends StringType
{
    public const NAME = 'user_email';

    #[Override]
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): mixed
    {
        return $value instanceof UserEmail ? $value->getValue() : $value;
    }

    /**
     * @throws \App\Core\Infrastructure\Doctrine\Dbal\Type\Email\EmailIsNotValid
     */
    #[Override]
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?UserEmail
    {
        return !empty($value) ? UserEmail::create((string)$value) : null;
    }
}
