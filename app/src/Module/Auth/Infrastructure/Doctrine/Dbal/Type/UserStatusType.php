<?php

declare(strict_types=1);

namespace App\Module\Auth\Infrastructure\Doctrine\Dbal\Type;

use App\Module\Auth\Domain\DomainModel\Entity\User\Embedded\UserStatus;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Override;

final class UserStatusType extends StringType
{
    public const NAME = 'user_status';

    #[Override]
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): mixed
    {
        return $value instanceof UserStatus ? $value->getValue() : $value;
    }

    /**
     * @throws \App\Core\Infrastructure\Doctrine\Dbal\Type\Status\StatusIsNotValid
     */
    #[Override]
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?UserStatus
    {
        return !empty($value) ? UserStatus::create((string)$value) : null;
    }
}
