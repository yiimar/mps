<?php

declare(strict_types=1);

namespace App\Module\Auth\Domain\DomainModel\Entity\User;

use App\Core\Application\Enum\DB\DbTAbles;
use App\Module\Auth\Domain\DomainModel\Entity\User\Embedded\UserStatus;
use App\Module\Auth\Infrastructure\Doctrine\Dbal\Type\UserEmailType;
use App\Module\Auth\Infrastructure\Doctrine\Dbal\Type\UserIdType;
use App\Module\Auth\Infrastructure\Doctrine\Dbal\Type\UserPasswordType;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DomainException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: DbTAbles::AUTH_USER->value)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ORM\HasLifecycleCallbacks]
final class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * Идентификатор.
     *
     * @var UserId
     */
    #[ORM\Id]
    #[ORM\Column(type: UserIdType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private UserId $id;

    /**
     * Email
     *
     * @var \App\Module\Auth\Domain\DomainModel\Entity\User\UserEmail
     */
    #[ORM\Column(type: UserEmailType::NAME, unique: true)]
    private UserEmail $email;

    /**
     * Дата регистрации
     *
     * @var \DateTimeImmutable
     */
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, length: 180)]
    private DateTimeImmutable $date;

    /**
     * Пароль
     *
     * @var UserPassword|null
     */
    #[ORM\Column(type: UserPasswordType::NAME, nullable: true)]
    private ?UserPassword $password = null;

    /**
     * Статус
     *
     * @var \App\Module\Auth\Domain\DomainModel\Entity\User\Embedded\UserStatus

     */
    #[ORM\Embedded(class: UserStatus::class)]
    private UserStatus $status;

    /**
     * Роли
     *
     * @var list<string|\App\Module\Auth\Domain\DomainModel\Entity\User\UserRole>
     */
    #[ORM\Column(type: 'json')]
    private array $roles;

    private function __construct(
        UserId            $id,
        DateTimeImmutable $date,
        UserEmail         $email,
        UserStatus        $status
    )
    {
        $this->id = $id;
        $this->date = $date;
        $this->email = $email;
        $this->status = $status;
        $this->roles = [UserRole::user()];
    }

    public function remove(): void
    {
        if (!$this->isWait()) {
            throw new DomainException('Unable to remove active user.');
        }
    }

    public function isWait(): bool
    {
        return $this->status->isWait();
    }

    public function isActive(): bool
    {
        return $this->status->isActive();
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getEmail(): UserEmail
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password->getValue();
    }

    /**
     * @throws \App\Module\Auth\Domain\DomainModel\Exception\ValueObject\User\UserPasswordIsEmpty
     */
    public function setPassword(string $password): self
    {
        $this->password = UserPassword::create($password);

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->email->getValue();
    }
}
