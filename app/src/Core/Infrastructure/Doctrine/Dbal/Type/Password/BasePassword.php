<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Doctrine\Dbal\Type\Password;

/**
 * @author Dmitry S
 */
readonly class BasePassword
{
    private function __construct(private string $password)
    {
    }

    /** @throws PasswordIsNotValid */
    public static function create(string $password): self
    {
        self::validate($password);

        return new self($password);
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