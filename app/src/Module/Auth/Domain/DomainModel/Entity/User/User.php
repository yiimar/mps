<?php

declare(strict_types=1);

namespace App\Module\Auth\Domain\DomainModel\Entity\User;

use App\Core\Infrastructure\Doctrine\Dbal\Type\Email\EmailType;
use App\Module\Auth\Domain\DomainModel\Entity\User\Embedded\Token;
use App\Module\Auth\Domain\DomainModel\Entity\User\Network\Network;
use App\Module\Auth\Domain\DomainModel\Entity\User\Network\UserNetwork;
use App\Module\Auth\Domain\DomainModel\Service\PasswordHasher;
use App\Module\Auth\Infrastructure\Doctrine\Dbal\Type\UserIdType;
use App\Module\Auth\Infrastructure\Doctrine\Dbal\Type\UserStatusType;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DomainException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'auth_user')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ORM\HasLifecycleCallbacks]
final class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * Идентификатор.
     *
     * @var \App\Module\Auth\Domain\DomainModel\Entity\User\UserId
     */
    #[ORM\Id]
    #[ORM\Column(type: UserIdType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private UserId $id;

    /**
     * Email
     *
     * @var \App\Module\Auth\Domain\DomainModel\Entity\User\Email
     */
    #[ORM\Column(type: EmailType::NAME, unique: true)]
    private Email $email;

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
     * @var string|null
     */
    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $password = null;

    /**
     * Статус
     *
     * @var \App\Module\Auth\Domain\DomainModel\Entity\User\Status
     */
    #[ORM\Column(type: UserStatusType::NAME, length: 16)]
    private Status $status;

    #[ORM\Embedded(class: Token::class)]
    private ?Token $joinConfirmToken = null;

    /**
     * Токен изменения пароля
     *
     * @var \App\Module\Auth\Domain\DomainModel\Entity\User\Embedded\Token|null
     */
    #[ORM\Embedded(class: Token::class)]
    private ?Token $passwordResetToken = null;

    /**
     * Новый пароль
     *
     * @var \App\Module\Auth\Domain\DomainModel\Entity\User\Email|null
     */
    #[ORM\Column(type: EmailType::NAME, nullable: true)]
    private ?Email $newEmail = null;

    /**
     * Токен нового пароля
     *
     * @var \App\Module\Auth\Domain\DomainModel\Entity\User\Embedded\Token|null
     */
    #[ORM\Embedded(class: Token::class)]
    private ?Token $newEmailToken = null;

    /**
     * Роли
     *
     * @var list<string>
     */
    #[ORM\Column(type: 'json')]
    private array $roles;

    /**
     * Социальные сети, использованные при аутентификации
     *
     * @var Collection<array-key,UserNetwork>
     */
    #[ORM\OneToMany(targetEntity: UserNetwork::class, mappedBy: 'user', cascade: ['all'], orphanRemoval: true)]
    private Collection $networks;

    private function __construct(
        UserId            $id,
        DateTimeImmutable $date,
        Email             $email,
        Status        $status
    )
    {
        $this->id = $id;
        $this->date = $date;
        $this->email = $email;
        $this->status = $status;
        $this->roles = [Role::user()];
        $this->networks = new ArrayCollection();
    }

    public static function joinByNetwork(
        UserId            $id,
        DateTimeImmutable $date,
        Email             $email,
        Network       $network
    ): self
    {
        $user = new self($id, $date, $email, Status::active());
        $user->networks->add(
            new UserNetwork(
                $user,
                $network
            )
        );
        return $user;
    }

    public static function requestJoinByEmail(
        UserId            $id,
        DateTimeImmutable $date,
        Email             $email,
        string            $password,
        Token             $token
    ): self
    {
        $user = new self($id, $date, $email, Status::wait());
        $user->password = $password;
        $user->joinConfirmToken = $token;
        return $user;
    }

    public function confirmJoin(string $token, DateTimeImmutable $date): void
    {
        if ($this->joinConfirmToken === null) {
            throw new DomainException('Confirmation is not required.');
        }
        $this->joinConfirmToken->validate($token, $date);
        $this->activate();
    }

    public function attachNetwork(Network $network): void
    {
        foreach ($this->networks as $existing) {
            if ($existing->getNetwork()->isEqualTo($network)) {
                throw new DomainException('Network is already attached.');
            }
        }
        $this->networks->add(new UserNetwork($this, $network));
        if ($this->isWait()) {
            $this->activate();
        }
    }

    public function requestPasswordReset(Token $token, DateTimeImmutable $date): void
    {
        if (!$this->isActive()) {
            throw new DomainException('User is not active.');
        }
        if ($this->passwordResetToken !== null && !$this->passwordResetToken->isExpiredTo($date)) {
            throw new DomainException('Resetting is already requested.');
        }
        $this->passwordResetToken = $token;
    }

    public function resetPassword(string $token, DateTimeImmutable $date, string $hash): void
    {
        if ($this->passwordResetToken === null) {
            throw new DomainException('Resetting is not requested.');
        }
        $this->passwordResetToken->validate($token, $date);
        $this->passwordResetToken = null;
        $this->password = $hash;
    }

    public function changePassword(string $current, string $new, PasswordHasher $hasher): void
    {
        if ($this->password === null) {
            throw new DomainException('User does not have an old password.');
        }
        if (!$hasher->validate($current, $this->password)) {
            throw new DomainException('Incorrect current password.');
        }
        $this->password = $hasher->hash($new);
    }

    public function requestEmailChanging(Token $token, DateTimeImmutable $date, Email $email): void
    {
        if (!$this->isActive()) {
            throw new DomainException('User is not active.');
        }
        if ($this->email->isEqualTo($email)) {
            throw new DomainException('Email is already same.');
        }
        if ($this->newEmailToken !== null && !$this->newEmailToken->isExpiredTo($date)) {
            throw new DomainException('Changing is already requested.');
        }
        $this->newEmail = $email;
        $this->newEmailToken = $token;
    }

    public function confirmEmailChanging(string $token, DateTimeImmutable $date): void
    {
        if ($this->newEmail === null || $this->newEmailToken === null) {
            throw new DomainException('Changing is not requested.');
        }
        $this->newEmailToken->validate($token, $date);
        $this->email = $this->newEmail;
        $this->newEmail = null;
        $this->newEmailToken = null;
    }

    public function changeRole(Role $role): void
    {
        $this->roles = [$role];
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

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getRole(): Role
    {
        return $this->roles[0];
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getJoinConfirmToken(): ?Token
    {
        return $this->joinConfirmToken;
    }

    public function getPasswordResetToken(): ?Token
    {
        return $this->passwordResetToken;
    }

    public function getNewEmail(): ?Email
    {
        return $this->newEmail;
    }

    public function getNewEmailToken(): ?Token
    {
        return $this->newEmailToken;
    }

    public function getNetworks(): array
    {
        return $this->networks->map(static fn (UserNetwork $network) => $network->getNetwork())->toArray();
    }

    #[ORM\PostLoad]
    public function checkEmbeds(): void
    {
        if ($this->joinConfirmToken && $this->joinConfirmToken->isEmpty()) {
            $this->joinConfirmToken = null;
        }
        if ($this->passwordResetToken && $this->passwordResetToken->isEmpty()) {
            $this->passwordResetToken = null;
        }
        if ($this->newEmailToken && $this->newEmailToken->isEmpty()) {
            $this->newEmailToken = null;
        }
    }

    private function activate(): void
    {
        if ($this->isActive()) {
            throw new DomainException('User is already active.');
        }

        $this->status = Status::active();
        $this->joinConfirmToken = null;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials(): void
    {
        return;
    }

    public function getUserIdentifier(): string
    {
        return $this->email->getValue();
    }
}
