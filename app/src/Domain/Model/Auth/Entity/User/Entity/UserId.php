<?php

declare(strict_types=1);

namespace App\Domain\Model\Auth\Entity\User\Entity;

use App\Infrastructure\Doctrine\Dbal\ValueObject\Ulid\BaseUlid;

final class UserId extends BaseUlid
{
    public function getValue(): string
    {
        return $this->uid;
    }
}