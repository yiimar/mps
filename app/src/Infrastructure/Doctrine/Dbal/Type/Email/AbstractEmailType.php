<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Dbal\Type\Email;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/**
 * @author Dmitry S
 */
abstract class AbstractEmailType extends Type
{
    public const NAME = 'email';

    public function getName(): string
    {
        return static::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getColumnDeclarationSQL('varchar', $column);
    }
}