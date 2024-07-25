<?php

declare(strict_types=1);

namespace App\Module\Admin\Auth\Infrastructure\Doctrine\Dbal\Type;

use App\Module\Admin\Auth\Domain\DomainModel\Entity\AdminEmail;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Override;

final class AdminEmailType extends StringType
{
    public const NAME = 'admin_email';

    #[Override]
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): mixed
    {
        return $value instanceof AdminEmail ? $value->getValue() : $value;
    }

    /**
     * @throws \App\Core\Infrastructure\Doctrine\Dbal\Type\Email\EmailIsNotValid
     */
    #[Override]
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?AdminEmail
    {
        return !empty($value) ? AdminEmail::create((string)$value) : null;
    }
}
