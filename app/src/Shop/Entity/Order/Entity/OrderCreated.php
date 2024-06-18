<?php

declare(strict_types=1);

namespace App\Shop\Entity\Order\Entity;

use App\Core\Infrastructure\Doctrine\Dbal\ValueObject\DateTimeImmutable\BaseDateTimeImmutable;

/**
 * @author Dmitry S
 */
readonly class OrderCreated extends BaseDateTimeImmutable
{
    public function isEqualTo(self $other): bool
    {
        return $this->__toString() === $other->__toString();
    }
}