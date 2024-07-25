<?php

declare(strict_types=1);

namespace App\Module\Admin\Auth\Infrastructure\Doctrine\Dbal\Type;

use App\Module\Admin\Auth\Domain\DomainModel\Entity\AdminRole;
use App\Module\Auth\Domain\DomainModel\Entity\User\UserRole;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

/**
 * @author Yiimar
 */
final class AdminRoleType extends StringType
{
    public const NAME = 'admin_role';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return $value instanceof AdminRole ? $value->getName() : (string)$value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?UserRole
    {
        return !empty($value) ? AdminRole::create($value) : null;
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