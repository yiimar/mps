<?php

declare(strict_types=1);


namespace App\Core\Infrastructure\Doctrine\Dbal\ValueObject;

use Webmozart\Assert\Assert;

/**
 * @author Dmitry S
 */
class BaseString
{
    public function __construct(private readonly string $value)
    {
        $this->validate();
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function validate(): self
    {
        Assert::true($this->value !== '');
        return $this;
    }
}