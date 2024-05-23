<?php

declare(strict_types=1);


namespace App\Domain\Model\Shop\Order\Entity;

use App\Infrastructure\Doctrine\Dbal\ValueObject\DateTimeImmutable\BaseDateTimeImmutable;

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