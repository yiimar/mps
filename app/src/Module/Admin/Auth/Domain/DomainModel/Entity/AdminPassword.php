<?php

declare(strict_types=1);

namespace App\Module\Admin\Auth\Domain\DomainModel\Entity;

use App\Core\Domain\DomainModel\ValueObject\Core\ValueObjectInterface;
use App\Core\Infrastructure\Doctrine\Dbal\Type\Password\PasswordIsEmpty;

/**
 * @author Yiimar
 */
final readonly class AdminPassword implements ValueObjectInterface
{
    public function __construct(
        private string $password //non-empty-string
    ) {
    }

    /**
     * @throws \App\Core\Infrastructure\Doctrine\Dbal\Type\Password\PasswordIsEmpty
     */
    public static function create($value): self
    {
        return new self(
            self::parseAndValidate($value)
        );
    }

    public static function defaultNamedConstructor(): callable
    {
        return [self::class, 'create'];
    }

    /** @return non-empty-string */
    public function toString(): string
    {
        return $this->password;
    }

    /**
     * @return non-empty-string
     *
     * @throws PasswordIsEmpty
     */
    private static function parseAndValidate(string $value): string
    {
        if ($value === '') {
            throw PasswordIsEmpty::create($value);
        }

        return $value;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function getValue(): string
    {
        return $this->getPassword();
    }
}