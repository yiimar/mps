<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Doctrine\Dbal\Type\Ulid;

use App\Core\Domain\DomainModel\ValueObject\Core\ValueObjectInterface;
use InvalidArgumentException;
use Symfony\Component\Uid\Ulid;

/**
 * @author Yiimar
 */
class BaseUlid extends Ulid implements ValueObjectInterface
{
    /**
     * @throws \App\Core\Infrastructure\Doctrine\Dbal\Type\Ulid\UlidIsNotValid
     */
    public function __construct(?string $ulid = null)
    {
        try {
            parent::__construct($ulid);
        } catch (InvalidArgumentException $e) {
            throw UlidIsNotValid::create($e->getMessage());
        }
    }

    public static function fromValue(string $value): static
    {
        return parent::fromString($value);
    }

    public function getValue(): string
    {
        return $this->uid;
    }

    public static function create(mixed $value): static
    {
        return static::fromValue($value);
    }

    public static function defaultNamedConstructor(): callable
    {
        return [static::class, 'create'];
    }

    public function toString(): string
    {
        return $this->uid;
    }
}