<?php

declare(strict_types=1);

namespace App\Module\Auth\Domain\DomainModel\Entity\User\Embedded;

use App\Core\Infrastructure\Doctrine\Dbal\Type\Status\StatusIsNotValid;
use App\Module\Auth\Infrastructure\Doctrine\Dbal\Type\UserStatusType;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

/**
 * @author Yiimar
 */
#[ORM\Embeddable]
final readonly class UserStatus
{
    public const WAIT = 'wait';
    public const ACTIVE = 'active';

    /**
     * @throws \App\Core\Infrastructure\Doctrine\Dbal\Type\Status\StatusIsNotValid
     */
    public function __construct(
        #[ORM\Column(type: UserStatusType::NAME, length: 16)]
        private string $value
    ) {
        try {
            self::validate($value);
        } catch (InvalidArgumentException $e) {
            throw StatusIsNotValid::create($e->getMessage());
        }
    }

    /**
     * @throws \App\Core\Infrastructure\Doctrine\Dbal\Type\Status\StatusIsNotValid
     */
    public static function create(mixed $value): self
    {
        return new self($value);
    }

    public static function wait(): self
    {
        return new self(self::WAIT);
    }

    public static function active(): self
    {
        return new self(self::ACTIVE);
    }

    protected static function validate(mixed $value): void
    {
        Assert::oneOf($value, [
            self::WAIT,
            self::ACTIVE,
        ]);
    }

    public function isWait(): bool
    {
        return $this->value === self::WAIT;
    }

    public function isActive(): bool
    {
        return $this->value === self::ACTIVE;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
