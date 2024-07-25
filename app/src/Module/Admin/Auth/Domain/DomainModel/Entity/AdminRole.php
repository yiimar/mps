<?php

declare(strict_types=1);

namespace App\Module\Admin\Auth\Domain\DomainModel\Entity;

use Webmozart\Assert\Assert;

/**
 * @author Yiimar
 */
final readonly class AdminRole
{
    public const ADMIN = 'ADMIN_ADMIN';

    public function __construct(private string $name)
    {
        self::validate($this->name);
    }

    public static function admin(): self
    {
        return new self(self::ADMIN);
    }

    public function isAdmin(): bool
    {
        return $this->name === self::ADMIN;
    }

    public function getValue(): string
    {
        return $this->name;
    }

    public function isEqual(self $role): bool
    {
        return $this->getValue() === $role->getValue();
    }

    public static function create(string $string): self
    {
        return new self($string);
    }

    public static function validate(string $name): void
    {
        Assert::oneOf($name, [
            self::ADMIN,
        ]);
    }
}