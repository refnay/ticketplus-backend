<?php

namespace App\Shared\Infrastructure;

use App\Shared\Domain\Services\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SymfonyMailer implements Mailer
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    public function send(Email $email): void
    {
        $this->mailer->send($email);
    }
}