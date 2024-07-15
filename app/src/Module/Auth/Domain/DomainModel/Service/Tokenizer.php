<?php

declare(strict_types=1);

namespace App\Module\Auth\Domain\DomainModel\Service;

use App\Core\Infrastructure\Doctrine\Dbal\Type\Ulid\BaseUlid;
use App\Module\Auth\Domain\DomainModel\Entity\User\Token;
use DateInterval;
use DateTimeImmutable;

final readonly class Tokenizer
{
    public function __construct(private DateInterval $interval) {}

    public function generate(DateTimeImmutable $date): Token
    {
        return new Token(
            BaseUlid::generate(),
            $date->add($this->interval)
        );
    }
}
