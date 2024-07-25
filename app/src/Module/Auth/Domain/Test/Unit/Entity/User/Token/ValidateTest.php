<?php

declare(strict_types=1);

namespace App\Module\Auth\Domain\Test\Unit\Entity\User\Token;

use App\Core\Infrastructure\Doctrine\Dbal\Type\Ulid\BaseUlid;
use App\Module\Auth\Domain\DomainModel\Entity\User\Embedded\Token;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ValidateTest extends TestCase
{
    public function testSuccess(): void
    {
        $token = new Token(
            $value = BaseUlid::generate(),
            $expires = new DateTimeImmutable()
        );

        $token->validate($value, $expires->modify('-1 secs'));
    }

    public function testWrong(): void
    {
        $token = new Token(
            BaseUlid::generate(),
            $expires = new DateTimeImmutable()
        );

        $this->expectExceptionMessage('Token is invalid.');
        $token->validate(BaseUlid::generate(), $expires->modify('-1 secs'));
    }

    public function testExpired(): void
    {
        $token = new Token(
            $value = BaseUlid::generate(),
            $expires = new DateTimeImmutable()
        );

        $this->expectExceptionMessage('Token is expired.');
        $token->validate($value, $expires->modify('+1 secs'));
    }
}
