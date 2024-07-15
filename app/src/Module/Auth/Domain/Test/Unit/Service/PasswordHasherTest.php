<?php

declare(strict_types=1);

namespace App\Module\Auth\Domain\Test\Unit\Service;

use App\Module\Auth\Domain\DomainModel\Service\PasswordHasher;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class PasswordHasherTest extends TestCase
{
    public function testHash(): void
    {
        $hasher = new PasswordHasher(16);

        $hash = $hasher->hash($password = 'new-password');

        self::assertNotEmpty($hash);
        self::assertNotSame($password, $hash);
    }

    public function testHashEmpty(): void
    {
        $hasher = new PasswordHasher(16);

        $this->expectException(InvalidArgumentException::class);
        $hasher->hash('');
    }

    public function testValidate(): void
    {
        $hasher = new PasswordHasher(16);

        $hash = $hasher->hash($password = 'new-password');

        self::assertTrue($hasher->validate($password, $hash));
        self::assertFalse($hasher->validate('wrong-password', $hash));
    }
}
