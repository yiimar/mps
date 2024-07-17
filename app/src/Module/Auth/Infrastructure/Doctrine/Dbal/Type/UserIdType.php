<?php

declare(strict_types=1);

namespace App\Module\Auth\Infrastructure\Doctrine\Dbal\Type;

use App\Core\Infrastructure\Doctrine\Dbal\Type\Ulid\UlidType;
use App\Module\Auth\Domain\DomainModel\Entity\User\UserId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Override;

/**
 * @author Yiimar
 */
class UserIdType extends UlidType
{
    public const NAME = 'auth_user_id';

    #[Override]
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof UserId ? $value->getValue() : (string)$value;
    }

    #[Override]
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?UserId
    {
        return !empty($value) ? new UserId((string)$value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}