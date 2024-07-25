<?php

declare(strict_types=1);

namespace App\Module\Auth\Domain\DomainModel\Entity\User;

use App\Module\Admin\Admin\DomainModel\Entity\Id;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use DomainException;

final readonly class UserRepository
{
    /**
     * @param \Doctrine\ORM\EntityManagerInterface $em
     * @param EntityRepository<User> $repo
     */
    public function __construct(private EntityManagerInterface $em, private EntityRepository $repo) {}

    public function findByJoinConfirmToken(string $token): ?User
    {
        return $this->repo->findOneBy(['joinConfirmToken.value' => $token]);
    }

    public function findByPasswordResetToken(string $token): ?User
    {
        return $this->repo->findOneBy(['passwordResetToken.value' => $token]);
    }

    public function findByNewEmailToken(string $token): ?User
    {
        return $this->repo->findOneBy(['newEmailToken.value' => $token]);
    }

    public function get(Id $id): User
    {
        $user = $this->repo->find($id->getValue());
        if ($user === null) {
            throw new DomainException('User is not found.');
        }
        return $user;
    }

    public function getByEmail(UserEmail $email): User
    {
        $user = $this->repo->findOneBy(['email' => $email->getValue()]);
        if ($user === null) {
            throw new DomainException('User is not found.');
        }
        return $user;
    }

    public function add(User $user): void
    {
        $this->em->persist($user);
    }

    public function remove(User $user): void
    {
        $this->em->remove($user);
    }
}
