<?php

declare(strict_types=1);

namespace App\Module\Auth\Domain\Test\Unit\Entity\User\User;

use App\Module\Auth\Domain\Test\Builder\UserBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class RemoveTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())
            ->build();

        $user->remove();
    }

    public function testActive(): void
    {
        $user = (new UserBuilder())
            ->active()
            ->build();

        $this->expectExceptionMessage('Unable to remove active user.');

        $user->remove();
    }
}
