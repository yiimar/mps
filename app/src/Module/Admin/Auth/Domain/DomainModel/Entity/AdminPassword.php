<?php

declare(strict_types=1);

namespace App\Module\Admin\Auth\Domain\DomainModel\Entity;

use App\Core\Domain\DomainModel\ValueObject\Core\ValueObjectInterface;

/**
 * @author Yiimar
 */
final readonly class AdminPassword implements ValueObjectInterface
{
    public function __construct(
        private string $password //non-empty-string
    ) {
    }

    public static function create($password): self
    {
        return new self(
            self::parseAndValidate($password)
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
     * @throws AdminPasswordIsEmpty
     */
    private static function parseAndValidate(string $password): string
    {
        if ($password === '') {
            throw AdminPasswordIsEmpty::create();
        }

        return $password;
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