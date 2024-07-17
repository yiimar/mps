<?php

declare(strict_types=1);

namespace App\Module\Auth\Domain\DomainModel\Service;

use App\Module\Auth\Domain\DomainModel\Entity\User\Embedded\Token;
use App\Module\Auth\Domain\DomainModel\Entity\User\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email as MimeEmail;
use Twig\Environment;

final readonly class NewEmailConfirmTokenSender
{
    public function __construct(private MailerInterface $mailer, private Environment $twig) {}

    /**
     * @throws \Twig\Error\SyntaxError
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\LoaderError
     */
    public function send(Email $email, Token $token): void
    {
        $message = (new MimeEmail())
            ->subject('New Email Confirmation')
            ->to($email->getValue())
            ->html($this->twig->render('auth/email/confirm.html.twig', ['token' => $token]), 'text/html');

        $this->mailer->send($message);
    }
}
