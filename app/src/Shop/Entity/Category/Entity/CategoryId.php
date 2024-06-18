<?php declare(strict_types=1);

namespace App\Shop\Entity\Category\Entity;

use App\Core\Infrastructure\Doctrine\Dbal\ValueObject\Ulid\BaseUlid;

final class CategoryId extends BaseUlid
{
    public function getValue(): string
    {
        return $this->uid;
    }
}