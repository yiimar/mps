<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Doctrine\Dbal\Type\Email;

use App\Core\Domain\DomainModel\ValueObject\Core\ValueObjectInterface;

/**
 * @author Yiimar
 */
readonly class BaseEmail implements ValueObjectInterface
{
    private function __construct(private string $email)
    {
    }

    /** @throws EmailIsNotValid */
    public static function create(mixed $value): static
    {
        self::validate($value);

        return new static($value);
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->email;
    }

    public function isEqualTo(self $other): bool
    {
        return $this->getValue() === $other->getValue();
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

    public static function defaultNamedConstructor(): callable
    {
        return [static::class, 'create'];
    }
}