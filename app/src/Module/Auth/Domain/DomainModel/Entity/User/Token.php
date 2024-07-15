<?php

declare(strict_types=1);

namespace App\Module\Auth\Domain\DomainModel\Entity\User;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DomainException;
use RuntimeException;
use Webmozart\Assert\Assert;
use function mb_strtolower;

#[ORM\Embeddable]
final readonly class Token
{
    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $value;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $expires;

    public function __construct(string $value, DateTimeImmutable $expires)
    {
        Assert::uuid($value);
        $this->value = mb_strtolower($value);
        $this->expires = $expires;
    }

    public function validate(string $value, DateTimeImmutable $date): void
    {
        if (!$this->isEqualTo($value)) {
            throw new DomainException('Token is invalid.');
        }
        if ($this->isExpiredTo($date)) {
            throw new DomainException('Token is expired.');
        }
    }

    public function isExpiredTo(DateTimeImmutable $date): bool
    {
        return $this->expires <= $date;
    }

    public function getValue(): string
    {
        return $this->value ?? throw new RuntimeException('Empty value.');
    }

    public function getExpires(): DateTimeImmutable
    {
        return $this->expires ?? throw new RuntimeException('Empty value.');
    }

    /**
     * @internal
     */
    public function isEmpty(): bool
    {
        return $this->value === null;
    }

    private function isEqualTo(string $value): bool
    {
        return $this->value === $value;
    }
}
