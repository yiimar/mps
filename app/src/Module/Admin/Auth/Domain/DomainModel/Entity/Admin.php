<?php

namespace App\Module\Admin\Auth\Domain\DomainModel\Entity;

use App\Core\Application\Enum\DB\DbTAbles;
use App\Core\Application\Enum\TableField\AdminAdmin;
use App\Module\Admin\Auth\Domain\DomainModel\Entity\Embedded\AdminStatus;
use App\Module\Admin\Auth\Infrastructure\Doctrine\Dbal\Type\AdminEmailType;
use App\Module\Admin\Auth\Infrastructure\Doctrine\Dbal\Type\AdminIdType;
use App\Module\Admin\Auth\Infrastructure\Doctrine\Dbal\Type\AdminPasswordType;
use App\Module\Admin\Auth\Infrastructure\Doctrine\Dbal\Type\AdminUsernameType;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: AdminRepository::class)]
#[ORM\Table(name: DbTAbles::ADMIN_ADMIN->value)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: [AdminAdmin::USER_NAME])]
#[ORM\HasLifecycleCallbacks]
class Admin
{
    #[ORM\Id]
    #[ORM\Column(type: AdminIdType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private AdminId $id;

    /**
     * Username
     *
     * @var AdminUsername
     */
    #[ORM\Column(type: AdminUsernameType::NAME, unique: true)]
    private AdminUsername $username;

    /**
     * Email
     *
     * @var AdminEmail
     */
    #[ORM\Column(type: AdminEmailType::NAME, unique: true)]
    private AdminEmail $email;

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
     * @var AdminPassword|null
     */
    #[ORM\Column(type: AdminPasswordType::NAME, nullable: true)]
    private ?AdminPassword $password = null;

    /**
     * Статус
     *
     * @var \App\Module\Auth\Domain\DomainModel\Entity\User\Embedded\UserStatus
     */
    #[ORM\Embedded(class: AdminStatus::class)]
    private AdminStatus $status;

    /**
     * @var list<string|AdminRole>
     */
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    public function getId(): AdminId
    {
        return $this->id;
    }

    /**
     * @return \App\Module\Admin\Auth\Domain\DomainModel\Entity\AdminUsername
     */
    public function getUsername(): AdminUsername
    {
        return $this->username;
    }

    /**
     * @param \App\Module\Admin\Auth\Domain\DomainModel\Entity\AdminUsername $username
     */
    public function setUsername(AdminUsername $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?AdminEmail
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = AdminEmail::create($email);

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password->getValue;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
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
