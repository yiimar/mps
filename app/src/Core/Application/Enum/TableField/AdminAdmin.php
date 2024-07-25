<?php

declare(strict_types=1);

namespace App\Core\Application\Enum\TableField;

/**
 * @author Yiimar
 */
enum AdminAdmin: string
{
    case ID = 'id';
    case USER_NAME = 'username';
    case EMAIL = 'email';
    case DATE = 'date';
    case PASSWORD = 'password';
    case ROLES = 'roles';
}
