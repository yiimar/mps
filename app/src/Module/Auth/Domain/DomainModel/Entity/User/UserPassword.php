<?php

declare(strict_types=1);

namespace App\Module\Auth\Domain\DomainModel\Entity\User;

use App\Core\Infrastructure\Doctrine\Dbal\Type\Password\BasePassword;

/**
 * @author Yiimar
 */
final readonly class UserPassword extends BasePassword
{
}