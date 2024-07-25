<?php

declare(strict_types=1);

namespace App\Module\Admin\Auth\Infrastructure\Doctrine\Dbal\Type;

use App\Core\Infrastructure\Doctrine\Dbal\Type\Ulid\AbstractUlidType;
use App\Module\Admin\Admin\DomainModel\Entity\AdminId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Override;

/**
 * @author Yiimar
 */
class AdminIdType extends AbstractUlidType
{
    public const NAME = 'admin_id';

    #[Override]
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof AdminId ? $value->getValue() : (string)$value;
    }

    #[Override]
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?AdminId
    {
        return !empty($value) ? AdminId::create((string)$value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}