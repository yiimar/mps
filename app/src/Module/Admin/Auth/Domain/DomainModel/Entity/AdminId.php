<?php

declare(strict_types=1);

namespace App\Module\Admin\Auth\Domain\DomainModel\Entity;

use App\Core\Infrastructure\Doctrine\Dbal\Type\Ulid\BaseUlid;

final class AdminId extends BaseUlid
{
}