<?php

declare(strict_types=1);

namespace App\Shop\Entity\Order\Entity;

use App\Auth\Domain\Entity\User\Entity\User;
use App\Shop\Entity\Category\Entity\Good\Entity\Good;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UlidGenerator;

/**
 * @author Dmitry S
 */
#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: 'order')]
#[ORM\HasLifecycleCallbacks]
final class Order
{
    #[ORM\Id]
    #[ORM\Column(type: OrderIdType::NAME)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UlidGenerator::class)]
    private OrderId $id;

    #[ORM\Column(type: OrderCreatedType::NAME)]
    private ?OrderCreated $order_created = null;

    /**
     * @var User
     */
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "orders")]
    private User $user;

    /**
     * @var Good[]
     */
    #[ORM\JoinColumn(name: "good_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    #[ORM\ManyToOne(targetEntity: Good::class, inversedBy: "goods")]
    private  $good;

    #[ORM\Column(type: OrderCostType::NAME)]
    private OrderCost $cost;

    /**
     * @var OrderCreated|null
     */
    private ?OrderCreated $created;

    /**
     * @param User $user
     * @param Good $good
     * @param OrderCreated|null $created
     */
    public function __construct(
        User $user,
        Good $good,
        ?OrderCreated $created
    )
    {
        $this->user = $user;
        $this->good = $good;
        $this->created = $created ?? OrderCreated::create();
    }

    /**
     * @return OrderId
     */
    public function getId(): OrderId
    {
        return $this->id;
    }

    /**
     * @return OrderCreated|null
     */
    public function getCreated(): ?OrderCreated
    {
        return $this->created;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Good
     */
    public function getGood(): Good
    {
        return $this->good;
    }

    /**
     * @return OrderCost
     */
    public function getCost(): OrderCost
    {
        return $this->cost;
    }
}