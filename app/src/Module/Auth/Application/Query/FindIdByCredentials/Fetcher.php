<?php

declare(strict_types=1);

namespace App\Module\Auth\Application\Query\FindIdByCredentials;

use App\Module\Auth\Domain\DomainModel\Entity\User\Embedded\UserStatus;
use App\Module\Auth\Domain\DomainModel\Service\PasswordHasher;
use Doctrine\DBAL\Connection;

final readonly class Fetcher
{
    public function __construct(private Connection $connection, private PasswordHasher $hasher) {}

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function fetch(Query $query): ?Command
    {
        $result = $this->connection->createQueryBuilder()
            ->select(
                'id',
                'status',
                'password_hash',
            )
            ->from('auth_users')
            ->where('email = :email')
            ->setParameter('email', mb_strtolower($query->email))
            ->executeQuery();

        /**
         * @var array{
         *     id: string,
         *     status: string,
         *     password_hash: ?string,
         * }|false $row
         */
        $row = $result->fetchAssociative();

        if ($row === false) {
            return null;
        }

        $hash = $row['password_hash'];

        if ($hash === null) {
            return null;
        }

        if (!$this->hasher->validate($query->password, $hash)) {
            return null;
        }

        return new Command(
            id: $row['id'],
            isActive: $row['status'] === UserStatus::ACTIVE
        );
    }
}
