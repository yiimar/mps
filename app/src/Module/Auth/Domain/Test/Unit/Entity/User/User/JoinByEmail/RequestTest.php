<?php

declare(strict_types=1);

namespace App\Module\Auth\Domain\Test\Unit\Entity\User\User\JoinByEmail;

use App\Core\Infrastructure\Doctrine\Dbal\Type\Ulid\BaseUlid;
use App\Module\Admin\Admin\DomainModel\Entity\Id;
use App\Module\Auth\Domain\DomainModel\Entity\User\UserEmail;
use App\Module\Auth\Domain\DomainModel\Entity\User\Embedded\Token;
use App\Module\Auth\Domain\DomainModel\Entity\User\UserRole;
use App\Module\Auth\Domain\DomainModel\Entity\User\User;
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
        $user = User::requestJoinByEmail(
            $id = Id::fromString(Id::generate()),
            $date = new DateTimeImmutable(),
            $email = UserEmail::create('mail@example.com'),
            $hash = 'hash',
            $token = new Token(BaseUlid::generate(), new DateTimeImmutable())
        );

        self::assertEquals($id, $user->getId());
        self::assertEquals($date, $user->getDate());
        self::assertEquals($email, $user->getEmail());
        self::assertSame($hash, $user->getPassword());
        self::assertEquals($token, $user->getJoinConfirmToken());

        self::assertTrue($user->isWait());
        self::assertFalse($user->isActive());

        self::assertSame(UserRole::USER, $user->getRole()->getName());
    }
}
