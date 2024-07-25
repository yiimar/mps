<?php
declare(strict_types=1);

namespace App\Module\Auth\Domain\ReadModel\User\UseCase\Exist;

use App\Module\Auth\Domain\DomainModel\Entity\User\UserEmail;
use Doctrine\ORM\EntityRepository;

/**
 * @author Yiimar
 */
final readonly class ByEmailFetcher
{
    public function __construct(
        private EntityRepository $repo
    )
    {}

    public function hasByEmail(UserEmail $email): bool
    {
        return $this->repo->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->andWhere('t.email = :email')
                ->setParameter(':email', $email->getValue())
                ->getQuery()->getSingleScalarResult() > 0;
    }
}