<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Message\Headers;

use function is_string;

/**
 * @author Dmitry S
 */
class Header
{
    public function __construct(
        private readonly string $originalName,
        private readonly string $normalizedName,
        private array  $values,
    )
    {}

    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    public function getNormalizedName(): string
    {
        return $this->normalizedName;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function addValue(string $value): self
    {
        $this->values[] = $value;

        return $this;
    }

    public function addValues(array|string $values): self
    {
        if (is_string($values)) {
            return $this->addValue($values);
        }

        $this->values = array_merge($this->values, $values);

        return $this;
    }
}