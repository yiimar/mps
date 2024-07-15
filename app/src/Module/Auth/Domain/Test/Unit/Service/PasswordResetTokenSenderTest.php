<?php

declare(strict_types=1);

namespace App\Module\Auth\Domain\Test\Unit\Service;

use App\Core\Infrastructure\Doctrine\Dbal\Type\Ulid\BaseUlid;
use App\Module\Auth\Domain\DomainModel\Entity\User\Token;
use App\Module\Auth\Domain\DomainModel\Entity\User\UserEmail;
use App\Module\Auth\Domain\DomainModel\Service\PasswordResetTokenSender;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email as MimeEmail;
use Twig\Environment;

/**
 * @internal
 */
final class PasswordResetTokenSenderTest extends TestCase
{
    /**
     * @throws \App\Core\Infrastructure\Doctrine\Dbal\Type\Email\EmailIsNotValid
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function testSuccess(): void
    {
        $to = UserEmail::create('user@app.test');
        $token = new Token(BaseUlid::generate(), new DateTimeImmutable());
        $confirmUrl = 'http://test/password/confirm?token=' . $token->getValue();

        $twig = $this->createMock(Environment::class);
        $twig->expects(self::once())->method('render')
            ->with('auth/password/confirm.html.twig', ['token' => $token])
            ->willReturn($body = '<a href="' . $confirmUrl . '">' . $confirmUrl . '</a>');

        $mailer = $this->createMock(MailerInterface::class);
        $mailer->expects(self::once())->method('send')
            ->willReturnCallback(static function (MimeEmail $message) use ($to, $body): void {
                self::assertSame($to->getValue(), $message->getTo()[0]->getAddress());
                self::assertSame('Password Reset', $message->getSubject());
                self::assertSame($body, $message->getHtmlBody());
            });

        $sender = new PasswordResetTokenSender($mailer, $twig);

        $sender->send($to, $token);
    }
}
