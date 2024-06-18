<?php

declare(strict_types=1);

namespace App\Auth\Domain\Entity\User\Entity;

use App\Core\Infrastructure\Doctrine\Dbal\Type\Email\AbstractEmailType;
use App\Core\Infrastructure\Doctrine\Dbal\ValueObject\Email\BaseEmail;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use function is_string;

/**
 * @author Dmitry S
 */
final class UserEmailType extends AbstractEmailType
{
    public const NAME = 'user_email';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): null | string
    {
        return $value instanceof UserEmail ? $value->getValue() : (string)$value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?BaseEmail
    {
        return is_string($value) ? new UserEmail($value) : null;
    }
}