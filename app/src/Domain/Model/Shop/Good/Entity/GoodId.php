<?php declare(strict_types=1);

namespace App\Domain\Model\Shop\Good\Entity;

use App\Infrastructure\Doctrine\Dbal\ValueObject\Ulid\BaseUlid;

final class GoodId extends BaseUlid
{
    public function getValue(): string
    {
        return $this->uid;
    }
}
