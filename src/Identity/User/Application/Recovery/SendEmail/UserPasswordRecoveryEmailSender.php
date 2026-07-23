<?php

namespace App\Identity\User\Application\Recovery\SendEmail;

use App\Identity\User\Domain\Exceptions\UserNotFound;
use App\Identity\User\Domain\Services\UserByEmailFinder;
use App\Identity\User\Domain\UserEmail;
use App\Shared\Domain\Services\Mailer;
use App\Shared\Domain\Services\PasswordResetter;
use App\Shared\Domain\UserId;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Throwable;

class UserPasswordRecoveryEmailSender
{
    public function __construct(
        private PasswordResetter $passwordResetter,
        private Mailer $mailer,
        private UserByEmailFinder $finder
    ) {}

    public function __invoke(UserEmail $email): void
    {
        try {
            $user = $this->finder->__invoke($email);
        } catch (UserNotFound) {
            return;
        }

        try {
            $token = $this->passwordResetter->generateToken(UserId::fromString($user->id()->value()));
        } catch (Throwable) {
            return;
        }

        $email = (new TemplatedEmail())
            ->from(new Address('no-reply@ticketplus.com', 'Ticketplus'))
            ->to($user->email()->value())
            ->subject('Recuperación de contraseña')
            ->htmlTemplate('emails/recovery-password-email.html.twig')
            ->context(['token' => $token]);

        $this->mailer->send($email);
    }
}
