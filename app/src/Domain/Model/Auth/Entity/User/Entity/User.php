<?php

declare(strict_types=1);

namespace App\Domain\Model\Auth\Entity\User\Entity;

use App\Domain\Model\Shop\Order\Entity\Order;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UlidGenerator;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'auth_user')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ORM\HasLifecycleCallbacks]
final class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * Идентификатор.
     *
     * @var \App\Domain\Model\Auth\Entity\User\Entity\UserId
     */
    #[ORM\Id]
    #[ORM\Column(type: UserIdType::NAME)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UlidGenerator::class)]
    private UserId $id;

    /**
     * Email адрес (уникален).
     *
     * @var \App\Domain\Model\Auth\Entity\User\Entity\UserEmail
     */
    #[ORM\Column(type: UserEmailType::NAME, length: 180, unique: true)]
    private UserEmail $email;

    /**
     * Роли пользователя
     *
     * @var Collection<array-key,UserRole>
     */
    #[ORM\Column(type: "json")]
    private Collection $roles;

    /**
     * Хэшированный пароль.
     *
     * @var UserPassword|null
     */
    #[ORM\Column(type: UserPasswordType::NAME, length: 60)]
    private ?UserPassword $password = null;

    /**
     * Заказы пользователя
     *
     * @var \Doctrine\Common\Collections\Collection|null
     */
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'user', orphanRemoval: true)]
    private ?Collection $orders;
    private function __construct(
        UserId $id,
        UserEmail $email,
        ?Collection $roles,
    )
    {
        $this->id = $id;
        $this->email = $email;
        $this->roles = $roles ?? new ArrayCollection();
        $this->orders =  new ArrayCollection();
    }
    public function getId(): UserId
    {
        return $this->id;
    }

    public function getEmail(): UserEmail
    {
        return $this->email;
    }

    public function setEmail(UserEmail $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->email->getValue();
    }

    /**
     * @return array
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return $this->roles->getValues();
    }

    public function setRoles(Collection $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection|null
     */
    public function getOrders(): ?Collection
    {
        return $this->orders;
    }

    /**
     * @param \App\Domain\Model\Shop\Order\Entity\Order $order
     * @return self
     */
    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
        }
        return $this;
    }

    /**
     * @param \App\Domain\Model\Shop\Order\Entity\Order $order
     * @return bool
     */
    public function removeOrder(Order $order): bool
    {
        return $this->orders->removeElement($order);
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
