<?php

declare(strict_types=1);

namespace App\Module\Auth\Domain\DomainModel\Entity\User;

use App\Module\Auth\Domain\DomainModel\Exception\ValueObject\User\UserPasswordIsEmpty;

/**
 * @author Yiimar
 */
final readonly class UserPassword
{
    private function __construct(
        private string $password //non-empty-string
    ) {
    }

    /**
     * @throws \App\Module\Auth\Domain\DomainModel\Exception\ValueObject\User\UserPasswordIsEmpty
     */
    public static function create(string $password): self
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
     * @throws UserPasswordIsEmpty
     */
    private static function parseAndValidate(string $password): string
    {
        if ($password === '') {
            throw UserPasswordIsEmpty::create();
        }

        return $password;
    }

    public function getValue(): string
    {
        return $this->password;
    }
}