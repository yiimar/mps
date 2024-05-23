<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Dbal\ValueObject\Money;

use Webmozart\Assert\Assert;

/**
 * @author Dmitry S
 */
class BaseMoneyAmount
{
    public function __construct(private int $value)
    {
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function setValue(int $amount): self
    {
        $this->value = $amount;

        return $this;
    }

    public function getConvertedAmount(): float
    {
        return round($this->value / 100, 2);
    }

    public function __toString(): string
    {
        return (string) $this->getConvertedAmount();
    }
}