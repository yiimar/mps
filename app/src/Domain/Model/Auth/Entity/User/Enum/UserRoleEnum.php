<?php

declare(strict_types=1);

namespace App\Domain\Model\Auth\Entity\User\Enum;

/**
 * @author Dmitry S
 */
enum UserRoleEnum: string
{
    case GUEST = 'guest';
    case USER = 'user';
}
