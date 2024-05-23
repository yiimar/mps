<?php

declare(strict_types=1);

namespace App\Domain\Model\Shop\Order\Entity;

use App\Infrastructure\Doctrine\Dbal\ValueObject\Ulid\BaseUlid;

final class OrderId extends BaseUlid
{
    public function getValue(): string
    {
        return $this->uid;
    }
}