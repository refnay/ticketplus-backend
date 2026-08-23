<?php

namespace App\Shared\Infrastructure\Mailer;

use App\Shared\Domain\Mailer\EmailMessage;
use App\Shared\Domain\Mailer\Mailer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

final readonly class SymfonyMailer implements Mailer
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    public function send(EmailMessage $message): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address($message->fromAddress(), $message->fromName()))
            ->to($message->to())
            ->subject($message->subject())
            ->htmlTemplate($message->template())
            ->context($message->context());

        foreach ($message->attachments() as $attachment) {
            $email->attach(
                $attachment->content(),
                $attachment->filename(),
                $attachment->contentType(),
            );
        }

        $this->mailer->send($email);
    }
}
