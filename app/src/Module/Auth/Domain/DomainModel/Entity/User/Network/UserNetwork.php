<?php

declare(strict_types=1);

namespace App\Module\Auth\Domain\DomainModel\Entity\User\Network;

use App\Module\Auth\Domain\DomainModel\Entity\User\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Webmozart\Assert\Assert;
use function mb_strtolower;

#[ORM\Entity(readOnly: true)]
#[ORM\Table(name: 'auth_user_networks')]
#[ORM\UniqueConstraint(columns: ['name', 'identity'])]
#[ORM\Embeddable]
final readonly class UserNetwork
{
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private UserNetworkId $id;

    #[ORM\Column(type: Types::STRING, length: 16)]
    private string $name;

    #[ORM\Column(type: Types::STRING, length: 16)]
    private string $identity;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'networks')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\ManyToOne(inversedBy: 'networks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $newEmail;

    public function __construct(
        string $name,
        string $identity
    )
    {
        Assert::notEmpty($name);
        Assert::notEmpty($identity);

        $this->name = mb_strtolower($name);
        $this->identity = mb_strtolower($identity);
    }


    public function isEqualTo(self $network): bool
    {
        return
            $this->getName() === $network->getName() &&
            $this->getIdentity() === $network->getIdentity();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getIdentity(): string
    {
        return $this->identity;
    }
}
