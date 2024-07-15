<?php

declare(strict_types=1);

namespace App\Module\Auth\Domain\DomainModel\Entity\User;

use App\Core\Infrastructure\Doctrine\Dbal\Type\Email\BaseEmail;

/**
 * @author Yiimar
 */
final readonly class UserEmail extends BaseEmail
{
}