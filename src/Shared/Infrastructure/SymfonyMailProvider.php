<?php

namespace App\Shared\Infrastructure;

use App\Shared\Domain\MailProvider;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SymfonyMailProvider implements MailProvider
{
    public function __construct(private MailerInterface  $mailer)
    {
    }

    public function send(Email $email): void
    {
        $this->mailer->send($email);
    }
}