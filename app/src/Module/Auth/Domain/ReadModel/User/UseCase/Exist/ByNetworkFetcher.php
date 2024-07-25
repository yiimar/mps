<?php
declare(strict_types=1);

namespace App\Module\Auth\Domain\ReadModel\User\UseCase\Exist;

use App\Module\Auth\Domain\DomainModel\Entity\User\Network\UserNetwork;
use Doctrine\ORM\EntityRepository;

/**
 * @author Yiimar
 */
final readonly class ByNetworkFetcher
{
    public function __construct(
        private EntityRepository $repo
    )
    {}

    public function hasByNetwork(UserNetwork $network): bool
    {
        return $this->repo->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->innerJoin('t.networks', 'n')
                ->andWhere('n.network.name = :name and n.network.identity = :identity')
                ->setParameter(':name', $network->getName())
                ->setParameter(':identity', $network->getIdentity())
                ->getQuery()->getSingleScalarResult() > 0;
    }
}