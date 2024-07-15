<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Doctrine\Dbal\Type\Core;

/**
 * @author Yiimar
 */
abstract class AbstractScalar
{
    protected static ValueIsNotValid $exception;

    private function __construct(
        private $value,
    )
    {
        static::validate($this->value);
    }

    /**
     * @throws ValueIsNotValid
     */
    abstract public static function validate($value): void;

    /** @throws ValueIsNotValid */
    public static function create($value): static
    {
        return new static($value);
    }

    /**
     * @return string|int|float
     */
    public function getValue(): float|int|string
    {
        return $this->value;
    }

    public function isEqualTo(self $other): bool
    {
        return $this->getValue() === $other->getValue();
    }

    public function toString(): string
    {
        return $this->value;
    }
}