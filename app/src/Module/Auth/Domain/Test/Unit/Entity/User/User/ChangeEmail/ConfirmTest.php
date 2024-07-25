<?php

declare(strict_types=1);

namespace App\Module\Auth\Domain\Test\Unit\Entity\User\User\ChangeEmail;

use App\Core\Infrastructure\Doctrine\Dbal\Type\Ulid\BaseUlid;
use App\Module\Auth\Domain\DomainModel\Entity\User\Embedded\Token;
use App\Module\Auth\Domain\DomainModel\Entity\User\UserEmail;
use App\Module\Auth\Domain\Test\Builder\UserBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ConfirmTest extends TestCase
{
    /**
     * @throws \App\Core\Infrastructure\Doctrine\Dbal\Type\Email\EmailIsNotValid
     */
    public function testSuccess(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now = new DateTimeImmutable();
        $token = new Token(BaseUlid::generate(), $now->modify('+1 day'));

        $user->requestEmailChanging($token, $now, $new = UserEmail::create('new-email@app.test'));

        $user->confirmEmailChanging($token->getValue(), $now);

        self::assertNull($user->getNewEmail());
        self::assertNull($user->getNewEmailToken());
        self::assertEquals($new, $user->getEmail());
    }

    /**
     * @throws \App\Core\Infrastructure\Doctrine\Dbal\Type\Email\EmailIsNotValid
     */
    public function testInvalidToken(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now = new DateTimeImmutable();
        $token = new Token(BaseUlid::generate(), $now->modify('+1 day'));

        $user->requestEmailChanging($token, $now, UserEmail::create('new-email@app.test'));

        $this->expectExceptionMessage('Token is invalid.');
        $user->confirmEmailChanging('invalid', $now);
    }

    /**
     * @throws \App\Core\Infrastructure\Doctrine\Dbal\Type\Email\EmailIsNotValid
     */
    public function testExpiredToken(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now = new DateTimeImmutable();
        $token = new Token(BaseUlid::generate(), $now);

        $user->requestEmailChanging($token, $now, UserEmail::create('new-email@app.test'));

        $this->expectExceptionMessage('Token is expired.');
        $user->confirmEmailChanging($token->getValue(), $now->modify('+1 day'));
    }

    public function testNotRequested(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now = new DateTimeImmutable();
        $token = new Token(BaseUlid::generate(), $now->modify('+1 day'));

        $this->expectExceptionMessage('Changing is not requested.');
        $user->confirmEmailChanging($token->getValue(), $now);
    }
}
