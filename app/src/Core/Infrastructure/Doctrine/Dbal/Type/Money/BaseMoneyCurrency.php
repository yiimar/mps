<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Doctrine\Dbal\Type\Money;

use Doctrine\DBAL\Types\StringType;

/**
 * @author Yiimar
 */
readonly class BaseMoneyCurrency
{
    public function __construct(private string $value)
    {}

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}