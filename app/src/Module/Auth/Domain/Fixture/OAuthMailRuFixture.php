<?php

declare(strict_types=1);

namespace App\Module\Auth\Domain\Fixture;

use App\Module\Admin\Admin\DomainModel\Entity\Id;
use App\Module\Auth\Domain\DomainModel\Entity\User\UserEmail;
use App\Module\Auth\Domain\DomainModel\Entity\User\Embedded\Token;
use App\Module\Auth\Domain\DomainModel\Entity\User\Network\UserNetwork;
use App\Module\Auth\Domain\DomainModel\Entity\User\User;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;
use Override;
use Symfony\Component\Uid\Ulid;

final class OAuthMailRuFixture extends AbstractFixture
{
    #[Override]
    public function load(ObjectManager $manager): void
    {
        $user = User::requestJoinByEmail(
            new Id(),
            $date = new DateTimeImmutable(),
            new UserEmail('mailru-active@app.test'),
            'hash',
            new Token($value = Ulid::generate(), $date->modify('+1 day'))
        );

        $user->confirmJoin($value, $date);

        $manager->persist($user);

        $user = User::requestJoinByEmail(
            new Id(),
            $date = new DateTimeImmutable(),
            new UserEmail('mailru-wait@app.test'),
            'hash',
            new Token(Ulid::generate(), $date->modify('+1 day'))
        );

        $manager->persist($user);

        $user = User::joinByNetwork(
            new Id(),
            new DateTimeImmutable(),
            new UserEmail('mailru-network@app.test'),
            new UserNetwork('mailru', '1000001')
        );

        $manager->persist($user);

        $manager->flush();
    }
}
