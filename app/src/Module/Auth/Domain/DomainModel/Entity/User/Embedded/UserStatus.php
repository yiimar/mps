<?php

declare(strict_types=1);

namespace App\Module\Auth\Domain\DomainModel\Entity\User\Embedded;

use App\Module\Auth\Infrastructure\Doctrine\Dbal\Type\AdminStatusType;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * @author Yiimar
 */
#[ORM\Embeddable]
final readonly class UserStatus
{
    public const WAIT = 'wait';
    public const ACTIVE = 'active';

    #[ORM\Column(type: AdminStatusType::NAME, length: 16)]
    private string $name;

    public function __construct(string $name)
    {
        Assert::oneOf($name, [
            self::WAIT,
            self::ACTIVE,
        ]);
        $this->name = $name;
    }

    public static function wait(): self
    {
        return new self(self::WAIT);
    }

    public static function active(): self
    {
        return new self(self::ACTIVE);
    }

    public function isWait(): bool
    {
        return $this->name === self::WAIT;
    }

    public function isActive(): bool
    {
        return $this->name === self::ACTIVE;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
