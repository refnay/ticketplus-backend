<?php

namespace App\Account\User\Application\Recovery\SendEmail;

use App\Account\User\Domain\Exceptions\UserNotFound;
use App\Account\User\Domain\Services\UserByEmailFinder;
use App\Account\User\Domain\UserEmail;
use App\Account\User\Domain\Recovery\PasswordResetter;
use App\Shared\Domain\Mailer\EmailMessage;
use App\Shared\Domain\Mailer\Mailer;
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
            $token = $this->passwordResetter->generateToken($user->id());
        } catch (Throwable) {
            return;
        }

        $this->mailer->send(new EmailMessage(
            'no-reply@ticketplus.com',
            'Ticketplus',
            $user->email()->value(),
            'Recuperación de contraseña',
            'email/recovery-password-email.html.twig',
            ['token' => $token],
        ));
    }
}
