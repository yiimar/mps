<?php

declare(strict_types=1);

namespace App\Module\Auth\Domain\Test\Unit\Entity\User;

use App\Module\Auth\Domain\DomainModel\Entity\User\UserStatus;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class StatusTest extends TestCase
{
    public function testSuccess(): void
    {
        $status = new UserStatus($name = UserStatus::WAIT);

        self::assertSame($name, $status->getName());
    }

    public function testIncorrect(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new UserStatus('none');
    }

    public function testWait(): void
    {
        $status = UserStatus::wait();

        self::assertTrue($status->isWait());
        self::assertFalse($status->isActive());
    }

    public function testActive(): void
    {
        $status = UserStatus::active();

        self::assertFalse($status->isWait());
        self::assertTrue($status->isActive());
    }
}
