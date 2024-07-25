<?php

declare(strict_types=1);

namespace App\Module\Auth\Infrastructure\Doctrine\Dbal\Type;

use App\Core\Infrastructure\Doctrine\Dbal\Type\Ulid\AbstractUlidType;
use App\Module\Admin\Admin\DomainModel\Entity\Id;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Override;

/**
 * @author Yiimar
 */
class AuthUserIdType extends AbstractUlidType
{
    public const NAME = 'auth_user_id';

    #[Override]
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof Id ? $value->getValue() : (string)$value;
    }

    #[Override]
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Id
    {
        return !empty($value) ? new Id((string)$value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}