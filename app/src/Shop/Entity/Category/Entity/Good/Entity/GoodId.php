<?php declare(strict_types=1);

namespace App\Shop\Entity\Category\Entity\Good\Entity;

use App\Core\Infrastructure\Doctrine\Dbal\ValueObject\Ulid\BaseUlid;

final class GoodId extends BaseUlid
{
    public function getValue(): string
    {
        return $this->uid;
    }
}