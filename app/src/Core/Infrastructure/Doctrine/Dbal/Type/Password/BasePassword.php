<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Doctrine\Dbal\Type\Password;

/**
 * @author Yiimar
 */
readonly class BasePassword
{
    private function __construct(private string $password)
    {
    }

    /** @throws PasswordIsNotValid */
    public static function create(string $password): static
    {
        static::validate($password);

        return new self($password);
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->password;
    }

    public function isEqualTo(self $other): bool
    {
        return $this->getValue() === $other->getValue();
    }

    public function toString(): string
    {
        return $this->password;
    }

    /** @throws PasswordIsNotValid */
    private static function validate(string $password): void
    {
        if (filter_var($password, FILTER_VALIDATE_EMAIL) === false) {
            throw PasswordIsNotValid::create($password);
        }
    }
}