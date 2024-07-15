<?php

declare(strict_types=1);

namespace App\Module\Auth\Domain\Test\Unit\Entity\User;

use App\Module\Auth\Domain\DomainModel\Entity\User\UserRole;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class RoleTest extends TestCase
{
    public function testSuccess(): void
    {
        $role = new UserRole($name = UserRole::ADMIN);

        self::assertSame($name, $role->getName());
    }

    public function testIncorrect(): void
    {
        $this->expectException(InvalidArgumentException::class);
        UserRole::create('none');
    }

    public function testEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        UserRole::create('');
    }

    public function testUserFactory(): void
    {
        $role = UserRole::user();

        self::assertSame(UserRole::USER, $role->getName());
    }
}
