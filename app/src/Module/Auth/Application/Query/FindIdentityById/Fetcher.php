<?php

declare(strict_types=1);

namespace App\Module\Auth\Application\Query\FindIdentityById;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Result;

final readonly class Fetcher
{
    public function __construct(private Connection $connection) {}

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function fetch(Query $query): ?Identity
    {
        $result = $this->connection->createQueryBuilder()
            ->select('id', 'role')
            ->from('auth_users')
            ->where('id = :id')
            ->setParameter('id', $query->id)
            ->executeQuery();

        if ($result instanceof Result) {
            $row = $result->fetchAssociative();
        }

        return $row === false
            ? null
            : new Identity(
                id: $row['id'],
                role: $row['role']
            );
    }
}
