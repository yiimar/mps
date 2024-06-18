<?php

declare(strict_types=1);

namespace App\Auth\Domain\Entity\User\Enum;

/**
 * @author Dmitry S
 */
enum UserRoleEnum: string
{
    case GUEST = 'guest';
    case USER = 'user';
}
