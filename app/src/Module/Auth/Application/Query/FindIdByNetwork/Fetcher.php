<?php

declare(strict_types=1);

namespace App\Module\Auth\Application\Query\FindIdByNetwork;

use Doctrine\DBAL\Connection;

final readonly class Fetcher
{
    public function __construct(
        private Connection $connection
    ) {}

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function fetch(Query $query): ?Command
    {
        $result = $this->connection->createQueryBuilder()
            ->select('n.user_id')
            ->from('auth_user_networks', 'n')
            ->where('network_name = :name AND network_identity = :identity')
            ->setParameter('name', $query->name)
            ->setParameter('identity', $query->identity)
            ->executeQuery();

        /**
         * @var array{
         *     user_id: string,
         * }|false $row
         */
        $row = $result->fetchAssociative();

        if ($row === false) {
            return null;
        }

        return new Command(
            id: $row['user_id']
        );
    }
}
