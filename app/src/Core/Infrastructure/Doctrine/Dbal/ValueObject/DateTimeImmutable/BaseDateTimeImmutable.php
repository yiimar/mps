<?php

declare(strict_types=1);


namespace App\Core\Infrastructure\Doctrine\Dbal\ValueObject\DateTimeImmutable;

use DateTimeImmutable;

/**
 * @author Dmitry S
 */
readonly class BaseDateTimeImmutable
{
    public function __construct(protected DateTimeImmutable $value)
    {
    }

    public static function create(): static
    {
        $dateTime = new DateTimeImmutable();
        return new static($dateTime);
    }

    public function getValue(): DateTimeImmutable
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value->format('Y-m-d H:i:s');
    }
}