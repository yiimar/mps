<?php

declare(strict_types=1);

namespace App\Module\Admin\Auth\Infrastructure\Doctrine\Dbal\Type;

use App\Module\Admin\Auth\Domain\DomainModel\Entity\Embedded\AdminStatus;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use Override;

final class AdminStatusType extends StringType
{
    public const NAME = 'admin_status';

    #[Override]
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): mixed
    {
        return $value instanceof AdminStatus ? $value->getName() : $value;
    }

    #[Override]
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?AdminStatus
    {
        return !empty($value) ? AdminStatus::create((string)$value) : null;
    }
}
