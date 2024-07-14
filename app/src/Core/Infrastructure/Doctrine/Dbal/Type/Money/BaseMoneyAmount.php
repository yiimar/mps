<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Doctrine\Dbal\Type\Money;

/**
 * @author Dmitry S
 */
readonly class BaseMoneyAmount
{
    public function __construct(private int $value)
    {}

    public function getValue(): int
    {
        return $this->value;
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