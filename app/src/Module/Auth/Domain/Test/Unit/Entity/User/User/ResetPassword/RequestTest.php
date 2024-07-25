<?php

declare(strict_types=1);

namespace App\Module\Auth\Domain\Test\Unit\Entity\User\User\ResetPassword;

use App\Core\Infrastructure\Doctrine\Dbal\Type\Ulid\BaseUlid;
use App\Module\Auth\Domain\DomainModel\Entity\User\Embedded\Token;
use App\Module\Auth\Domain\Test\Builder\UserBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class RequestTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 hour'));

        $user->requestPasswordReset($token, $now);

        self::assertNotNull($user->getPasswordResetToken());
        self::assertEquals($token, $user->getPasswordResetToken());
    }

    public function testAlready(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 hour'));

        $user->requestPasswordReset($token, $now);

        $this->expectExceptionMessage('Resetting is already requested.');
        $user->requestPasswordReset($token, $now);
    }

    public function testExpired(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 hour'));
        $user->requestPasswordReset($token, $now);

        $newDate = $now->modify('+2 hours');
        $newToken = $this->createToken($newDate->modify('+1 hour'));
        $user->requestPasswordReset($newToken, $newDate);

        self::assertEquals($newToken, $user->getPasswordResetToken());
    }

    public function testNotActive(): void
    {
        $user = (new UserBuilder())->build();

        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 hour'));

        $this->expectExceptionMessage('User is not active.');
        $user->requestPasswordReset($token, $now);
    }

    private function createToken(DateTimeImmutable $date): Token
    {
        return new Token(
            BaseUlid::generate(),
            $date
        );
    }
}
