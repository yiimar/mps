<?php

declare(strict_types=1);


namespace App\Domain\Model\Auth\Entity\User\Entity;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

/**
 * @author Dmitry S
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
        return !empty($value) ? new UserRole($value) : null;
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