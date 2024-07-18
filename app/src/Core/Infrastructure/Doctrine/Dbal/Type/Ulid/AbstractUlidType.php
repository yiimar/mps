<?php

declare(strict_types=1);


namespace App\Core\Infrastructure\Doctrine\Dbal\Type\Ulid;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/**
 * @author Dmitry S
 */
abstract class AbstractUlidType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        if ($this->hasNativeGuidType($platform)) {
            return $platform->getGuidTypeDeclarationSQL($column);
        }

        return $platform->getBinaryTypeDeclarationSQL([
            'length' => 16,
            'fixed' => true,
        ]);

    }

    private function hasNativeGuidType(AbstractPlatform $platform): bool
    {
        return $platform->getGuidTypeDeclarationSQL([]) !== $platform->getStringTypeDeclarationSQL(['fixed' => true, 'length' => 36]);
    }
}