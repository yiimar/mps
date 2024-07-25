<?php

declare(strict_types=1);

namespace App\Module\Auth\Domain\DomainModel\Entity\User;

use Webmozart\Assert\Assert;

/**
 * @author Yiimar
 */
final readonly class UserRole
{
    public const USER = 'ROLE_USER';
    public const ADMIN = 'ROLE_ADMIN';

    public function __construct(private string $name)
    {
        self::validate($this->name);
    }

    public static function user(): self
    {
        return new self(self::USER);
    }

    public static function create(string $string): self
    {
        return new self($string);
    }

    public function isUser(): bool
    {
        return $this->name === self::USER;
    }

    public function isEqual(self $role): bool
    {
        return $this->getName() === $role->getName();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): string
    {
        return $this->name;
    }

    public static function validate(string $name): void
    {
        Assert::oneOf($name, [
            self::USER,
            self::ADMIN,
        ]);
    }
}