<?php

declare(strict_types=1);

namespace App\Module\Auth\Domain\Fixture;

use App\Module\Admin\Admin\DomainModel\Entity\Id;
use App\Module\Auth\Domain\DomainModel\Entity\User\UserEmail;
use App\Module\Auth\Domain\DomainModel\Entity\User\Embedded\Token;
use App\Module\Auth\Domain\DomainModel\Entity\User\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Override;
use Symfony\Component\Uid\Ulid;

final class UserFixture extends Fixture
{
    // 'password'
    private const PASSWORD_HASH = '$2y$12$qwnND33o8DGWvFoepotSju7eTAQ6gzLD/zy6W8NCVtiHPbkybz.w6';

    /**
     * @throws \App\Core\Infrastructure\Doctrine\Dbal\Type\Email\EmailIsNotValid
     */
    #[Override]
    public function load(ObjectManager $manager): void
    {
        $user = User::requestJoinByEmail(
            Id::fromString('00000000-0000-0000-0000-000000000001'),
            $date = new DateTimeImmutable('-30 days'),
            UserEmail::create('user@app.test'),
            self::PASSWORD_HASH,
            new Token($value = Ulid::generate(), $date->modify('+1 day'))
        );

        $user->confirmJoin($value, $date);

        $manager->persist($user);

        $manager->flush();
    }
}
