<?php

declare(strict_types=1);

namespace App\Shop\Entity\Order\Entity;

use App\Core\Infrastructure\Doctrine\Dbal\ValueObject\Ulid\BaseUlid;

final class OrderId extends BaseUlid
{
    public function getValue(): string
    {
        return $this->uid;
    }
}