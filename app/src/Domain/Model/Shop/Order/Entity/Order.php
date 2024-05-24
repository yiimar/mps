<?php

declare(strict_types=1);


namespace App\Domain\Model\Shop\Order\Entity;

use App\Domain\Model\Auth\Entity\User\Entity\User;
use App\Domain\Model\Shop\Category\Entity\Good\Entity\Good;
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
     * @var \App\Domain\Model\Auth\Entity\User\Entity\User
     */
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "orders")]
    private User $user;

    /**
     * @var \App\Domain\Model\Shop\Category\Entity\Good\Entity\Good
     */
    #[ORM\JoinColumn(name: "good_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    #[ORM\ManyToOne(targetEntity: Good::class, inversedBy: "goods")]
    private Good $good;

    #[ORM\Column(type: OrderCostType::NAME)]
    private OrderCost $cost;

    /**
     * @var \App\Domain\Model\Shop\Order\Entity\OrderCreated|null
     */
    private ?OrderCreated $created;

    /**
     * @param \App\Domain\Model\Auth\Entity\User\Entity\User $user
     * @param \App\Domain\Model\Shop\Category\Entity\Good\Entity\Good $good
     * @param \App\Domain\Model\Shop\Order\Entity\OrderCreated|null $created
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
     * @return \App\Domain\Model\Shop\Order\Entity\OrderId
     */
    public function getId(): OrderId
    {
        return $this->id;
    }

    /**
     * @return \App\Domain\Model\Shop\Order\Entity\OrderCreated|null
     */
    public function getCreated(): ?OrderCreated
    {
        return $this->created;
    }

    /**
     * @return \App\Domain\Model\Auth\Entity\User\Entity\User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return \App\Domain\Model\Shop\Category\Entity\Good\Entity\Good
     */
    public function getGood(): Good
    {
        return $this->good;
    }

    /**
     * @return \App\Domain\Model\Shop\Order\Entity\OrderCost
     */
    public function getCost(): OrderCost
    {
        return $this->cost;
    }
}