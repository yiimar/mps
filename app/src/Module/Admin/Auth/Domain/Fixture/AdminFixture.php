<?php

declare(strict_types=1);

namespace App\Module\Admin\Auth\Domain\Fixture;

use App\Module\Admin\Auth\Domain\DomainModel\Entity\Admin;
use App\Module\Admin\Auth\Domain\DomainModel\Entity\AdminId;
use App\Module\Auth\Domain\DomainModel\Entity\User\UserEmail;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Override;

final class AdminFixture extends Fixture
{
    // 'password'
    private const PASSWORD_HASH = '$2y$12$qwnND33o8DGWvFoepotSju7eTAQ6gzLD/zy6W8NCVtiHPbkybz.w6';

    /**
     * @throws \App\Core\Infrastructure\Doctrine\Dbal\Type\Email\EmailIsNotValid
     */
    #[Override]
    public function load(ObjectManager $manager): void
    {
        $user = Admin::requestJoinByEmail(
            AdminId::fromString('00000000-0000-0000-0000-000000000001'),
            $date = new DateTimeImmutable('-30 days'),
            UserEmail::create('user@app.test'),
            self::PASSWORD_HASH,
        );

        $manager->persist($user);

        $manager->flush();
    }
}
