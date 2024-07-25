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
final class ResetTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 hour'));

        $user->requestPasswordReset($token, $now);

        $user->resetPassword($token->getValue(), $now, $hash = 'hash');

        self::assertNull($user->getPasswordResetToken());
        self::assertSame($hash, $user->getPassword());
    }

    public function testInvalidToken(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 hour'));

        $user->requestPasswordReset($token, $now);

        $this->expectExceptionMessage('Token is invalid.');
        $user->resetPassword(BaseUlid::generate(), $now, 'hash');
    }

    public function testExpiredToken(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 hour'));

        $user->requestPasswordReset($token, $now);

        $this->expectExceptionMessage('Token is expired.');
        $user->resetPassword($token->getValue(), $now->modify('+1 day'), 'hash');
    }

    public function testNotRequested(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now = new DateTimeImmutable();

        $this->expectExceptionMessage('Resetting is not requested.');
        $user->resetPassword(BaseUlid::generate(), $now, 'hash');
    }

    private function createToken(DateTimeImmutable $date): Token
    {
        return new Token(
            BaseUlid::generate(),
            $date
        );
    }
}
