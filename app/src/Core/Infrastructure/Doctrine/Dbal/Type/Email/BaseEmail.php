<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Doctrine\Dbal\Type\Email;

/**
 * @author Yiimar
 */
readonly class BaseEmail
{
    private function __construct(private string $email)
    {
    }

    /** @throws EmailIsNotValid */
    public static function create(string $email): self
    {
        self::validate($email);

        return new self($email);
    }

    public function toString(): string
    {
        return $this->email;
    }

    /** @throws EmailIsNotValid */
    private static function validate(string $email): void
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw EmailIsNotValid::create($email);
        }
    }
}