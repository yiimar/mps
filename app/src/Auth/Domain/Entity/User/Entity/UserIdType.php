<?php

declare(strict_types=1);

namespace App\Auth\Domain\Entity\User\Entity;

use App\Core\Infrastructure\Doctrine\Dbal\Type\Ulid\AbstractUlidType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Override;

/**
 * @author Dmitry S
 */
final class UserIdType extends AbstractUlidType
{
    public const NAME = 'user_id';

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
}