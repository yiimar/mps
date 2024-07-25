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
final class RequestTest extends TestCase
{
    /**
     * @throws \App\Core\Infrastructure\Doctrine\Dbal\Type\Email\EmailIsNotValid
     */
    public function testSuccess(): void
    {
        $user = (new UserBuilder())
            ->withEmail($old = UserEmail::create('old-email@app.test'))
            ->active()
            ->build();

        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 day'));

        $user->requestEmailChanging($token, $now, $new = UserEmail::create('new-email@app.test'));

        self::assertEquals($token, $user->getNewEmailToken());
        self::assertEquals($old, $user->getEmail());
        self::assertEquals($new, $user->getNewEmail());
    }

    /**
     * @throws \App\Core\Infrastructure\Doctrine\Dbal\Type\Email\EmailIsNotValid
     */
    public function testSame(): void
    {
        $user = (new UserBuilder())
            ->withEmail($old = UserEmail::create('old-email@app.test'))
            ->active()
            ->build();

        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 day'));

        $this->expectExceptionMessage('Email is already same.');
        $user->requestEmailChanging($token, $now, $old);
    }

    /**
     * @throws \App\Core\Infrastructure\Doctrine\Dbal\Type\Email\EmailIsNotValid
     */
    public function testAlready(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 day'));

        $user->requestEmailChanging($token, $now, $email = UserEmail::create('new-email@app.test'));

        $this->expectExceptionMessage('Changing is already requested.');
        $user->requestEmailChanging($token, $now, $email);
    }

    /**
     * @throws \App\Core\Infrastructure\Doctrine\Dbal\Type\Email\EmailIsNotValid
     */
    public function testExpired(): void
    {
        $user = (new UserBuilder())->active()->build();

        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 hour'));
        $user->requestEmailChanging($token, $now, UserEmail::create('temp-email@app.test'));

        $newDate = $now->modify('+2 hours');
        $newToken = $this->createToken($newDate->modify('+1 hour'));
        $user->requestEmailChanging($newToken, $newDate, $newEmail = UserEmail::create('new-email@app.test'));

        self::assertEquals($newToken, $user->getNewEmailToken());
        self::assertEquals($newEmail, $user->getNewEmail());
    }

    /**
     * @throws \App\Core\Infrastructure\Doctrine\Dbal\Type\Email\EmailIsNotValid
     */
    public function testNotActive(): void
    {
        $now = new DateTimeImmutable();
        $token = $this->createToken($now->modify('+1 day'));

        $user = (new UserBuilder())->build();

        $this->expectExceptionMessage('User is not active.');
        $user->requestEmailChanging($token, $now, UserEmail::create('temp-email@app.test'));
    }

    private function createToken(DateTimeImmutable $date): Token
    {
        return new Token(
            BaseUlid::generate(),
            $date
        );
    }
}
