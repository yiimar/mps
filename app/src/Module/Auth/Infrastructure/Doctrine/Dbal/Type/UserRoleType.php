<?php

declare(strict_types=1);

namespace App\Module\Auth\Infrastructure\Doctrine\Dbal\Type;

use App\Module\Auth\Domain\DomainModel\Entity\User\UserRole;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

/**
 * @author Yiimar
 */
final class UserRoleType extends StringType
{
    public const NAME = 'user_role';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return $value instanceof UserRole ? $value->getName() : (string)$value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?UserRole
    {
        return !empty($value) ? UserRole::create((string)$value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform) : bool
    {
        return true;
    }

}