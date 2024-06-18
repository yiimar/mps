<?php

declare(strict_types=1);

namespace App\Auth\Domain\Entity\User\Entity;

use App\Core\Infrastructure\Doctrine\Dbal\ValueObject\Ulid\BaseUlid;

final class UserId extends BaseUlid
{
    public function getValue(): string
    {
        return $this->uid;
    }
}