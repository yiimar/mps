<?php declare(strict_types=1);

namespace App\Domain\Model\Shop\Category\Entity;

use App\Infrastructure\Doctrine\Dbal\ValueObject\Ulid\BaseUlid;

final class CategoryId extends BaseUlid
{
    public function getValue(): string
    {
        return $this->uid;
    }
}
