<?php

declare(strict_types=1);

namespace App\Module\Auth\Domain\Fixture;

use App\Module\Auth\Domain\DomainModel\Entity\User\UserEmail;
use App\Module\Auth\Domain\DomainModel\Entity\User\Embedded\Token;
use App\Module\Auth\Domain\DomainModel\Entity\User\User;
use App\Module\Auth\Domain\Entity\User\Id;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Override;

final class UserJoinFixture extends AbstractFixture
{
    private const PASSWORD_HASH = '$2y$12$qwnND33o8DGWvFoepotSju7eTAQ6gzLD/zy6W8NCVtiHPbkybz.w6';

    #[Override]
    public function load(ObjectManager $manager): void
    {
        $user = User::requestJoinByEmail(
            Id::generate(),
            new DateTimeImmutable('-1 hours'),
            new UserEmail('join-existing@app.test'),
            self::PASSWORD_HASH,
            new Token('00000000-0000-0000-0000-100000000001', new DateTimeImmutable('+1 hours'))
        );
        $manager->persist($user);

        $manager->flush();
    }
}
