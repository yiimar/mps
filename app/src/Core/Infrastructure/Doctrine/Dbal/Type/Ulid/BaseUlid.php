<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Doctrine\Dbal\Type\Ulid;

use Symfony\Component\Uid\Ulid;

/**
 * @author Yiimar
 */
class BaseUlid extends Ulid
{
    public static function fromValue(string $value): static
    {
        return parent::fromString($value);
    }

    public function getValue(): string
    {
        return $this->toBinary();
    }
}