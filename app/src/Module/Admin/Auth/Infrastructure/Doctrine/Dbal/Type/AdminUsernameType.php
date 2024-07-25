<?php

declare(strict_types=1);

namespace App\Module\Admin\Auth\Infrastructure\Doctrine\Dbal\Type;

use App\Module\Auth\Domain\DomainModel\Entity\User\Password;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Override;

/**
 * @author Yiimar
 */
final class AdminUsernameType extends StringType
{
    public const NAME = 'admin_username';

    #[Override]
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof AdminUsername ? $value->getValue() : (string)$value;
    }

    #[Override]
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?AdminUsername
    {
        return !empty($value) ? AdminUsername::create((string)$value) : null;
    }
}