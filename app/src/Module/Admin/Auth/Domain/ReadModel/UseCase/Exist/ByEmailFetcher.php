<?php

declare(strict_types=1);

namespace App\Module\Admin\Auth\Domain\ReadModel\UseCase\Exist;

use App\Module\Admin\Auth\Domain\DomainModel\Entity\AdminEmail;
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

    public function hasByEmail(AdminEmail $email): bool
    {
        return $this->repo->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->andWhere('t.email = :email')
                ->setParameter(':email', $email->getValue())
                ->getQuery()->getSingleScalarResult() > 0;
    }
}