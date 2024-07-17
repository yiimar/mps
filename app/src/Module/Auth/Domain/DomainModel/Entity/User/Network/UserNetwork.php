<?php

declare(strict_types=1);

namespace App\Module\Auth\Domain\DomainModel\Entity\User\Network;

use App\Module\Auth\Domain\DomainModel\Entity\User\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;

#[ORM\Entity(readOnly: true)]
#[ORM\Table(name: 'auth_user_network')]
#[ORM\UniqueConstraint(columns: ['network_name', 'network_identity'])]
#[ORM\Embeddable]
final readonly class UserNetwork
{
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private UserNetworkId $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'networks')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\Embedded(class: Network::class)]
    private Network $network;

    public function __construct(
        User $user,
        Network $network
    )
    {
        $this->id = UserNetworkId::fromString(UserNetworkId::generate());
        $this->user = $user;
        $this->network = $network;
    }

    /**
     * @return \App\Module\Auth\Domain\DomainModel\Entity\User\Network\UserNetworkId
     */
    public function getId(): UserNetworkId
    {
        return $this->id;
    }

    /**
     * @return \App\Module\Auth\Domain\DomainModel\Entity\User\User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    public function getNetwork(): Network
    {
        return $this->network;
    }
}
