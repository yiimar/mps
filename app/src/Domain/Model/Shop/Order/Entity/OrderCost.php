<?php

declare(strict_types=1);


namespace App\Domain\Model\Shop\Order\Entity;

use App\Infrastructure\Doctrine\Dbal\ValueObject\Money\BaseMoneyAmount;

/**
 * @author Dmitry S
 */
final class OrderCost extends BaseMoneyAmount
{
    public function isEqualTo(self $other): bool
    {
        return $this->getValue() === $other->getValue();
    }
}