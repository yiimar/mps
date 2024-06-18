<?php

declare(strict_types=1);

namespace App\Auth\Domain\Entity\User\Entity;

use App\Core\Infrastructure\Doctrine\Dbal\ValueObject\Email\BaseEmail;

/**
 * @author Dmitry S
 */
final class UserEmail extends BaseEmail
{
    public function isEqualTo(self $other): bool
    {
        return $this->getValue() === $other->getValue();
    }
}