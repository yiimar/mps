<?php

declare(strict_types=1);

namespace App\Module\Auth\Application\Query\FindIdByEmail;

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
            ->select('u.id')
            ->from('auth_users', 'u')
            ->where('email = :email')
            ->setParameter('email', mb_strtolower($query->email))
            ->executeQuery();

        /**
         * @var array{
         *     id: string,
         * }|false $row
         */
        $row = $result->fetchAssociative();

        if ($row === false) {
            return null;
        }

        return new Command(
            id: $row['id']
        );
    }
}
