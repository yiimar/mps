<?php

declare(strict_types=1);

namespace App\Module\Auth\Domain\Test\Builder;

use App\Core\Infrastructure\Doctrine\Dbal\Type\Ulid\BaseUlid;
use App\Module\Admin\Admin\DomainModel\Entity\Id;
use App\Module\Auth\Domain\DomainModel\Entity\User\UserEmail;
use App\Module\Auth\Domain\DomainModel\Entity\User\Embedded\Token;
use App\Module\Auth\Domain\DomainModel\Entity\User\Network\UserNetwork;
use App\Module\Auth\Domain\DomainModel\Entity\User\User;
use DateTimeImmutable;

final class UserBuilder
{
    private Id $id;
    private UserEmail $email;
    private string $passwordHash;
    private DateTimeImmutable $date;
    private Token $joinConfirmToken;
    private bool $active = false;
    private ?UserNetwork $networkIdentity = null;

    /**
     * @throws \App\Core\Infrastructure\Doctrine\Dbal\Type\Email\EmailIsNotValid
     */
    public function __construct()
    {
        $this->id = Id::fromString(Id::generate());
        $this->email = UserEmail::create('mail@example.com');
        $this->passwordHash = 'hash';
        $this->date = new DateTimeImmutable();
        $this->joinConfirmToken = new Token(BaseUlid::generate(), $this->date->modify('+1 day'));
    }

    public function withId(Id $id): self
    {
        $clone = clone $this;
        $clone->id = $id;
        return $clone;
    }

    public function withJoinConfirmToken(Token $token): self
    {
        $clone = clone $this;
        $clone->joinConfirmToken = $token;
        return $clone;
    }

    public function withEmail(UserEmail $email): self
    {
        $clone = clone $this;
        $clone->email = $email;
        return $clone;
    }

    public function withPasswordHash(string $passwordHash): self
    {
        $clone = clone $this;
        $clone->passwordHash = $passwordHash;
        return $clone;
    }

    public function viaNetwork(?UserNetwork $network = null): self
    {
        $clone = clone $this;
        $clone->networkIdentity = $network ?? new UserNetwork('vk', '0000001');
        return $clone;
    }

    public function active(): self
    {
        $clone = clone $this;
        $clone->active = true;
        return $clone;
    }

    public function build(): User
    {
        if ($this->networkIdentity !== null) {
            return User::joinByNetwork(
                $this->id,
                $this->date,
                $this->email,
                $this->networkIdentity
            );
        }

        $user = User::requestJoinByEmail(
            $this->id,
            $this->date,
            $this->email,
            $this->passwordHash,
            $this->joinConfirmToken
        );

        if ($this->active) {
            $user->confirmJoin(
                $this->joinConfirmToken->getValue(),
                $this->joinConfirmToken->getExpires()->modify('-1 day')
            );
        }

        return $user;
    }
}
