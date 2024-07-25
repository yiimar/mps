<?php

declare(strict_types=1);

namespace App\Module\Auth\Infrastructure\Doctrine\Dbal\Type;

use App\Module\Auth\Domain\DomainModel\Entity\User\Embedded\UserStatus;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Override;

final class AuthUserStatusType extends StringType
{
    public const NAME = 'auth_user_status';

    #[Override]
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): mixed
    {
        return $value instanceof UserStatus ? $value->getName() : $value;
    }

    #[Override]
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?UserStatus
    {
        return !empty($value) ? new UserStatus((string)$value) : null;
    }
}
