<?php

declare(strict_types=1);

namespace App\Auth\Domain\Entity\User\Entity;

use App\Core\Infrastructure\Doctrine\Dbal\ValueObject\Password\BasePassword;

/**
 * @author Dmitry S
 */
final class UserPassword extends BasePassword
{
    public function isEqualTo(self $other): bool
    {
        return $this->getValue() === $other->getValue();
    }
}