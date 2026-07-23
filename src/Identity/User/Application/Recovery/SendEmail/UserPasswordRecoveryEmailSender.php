<?php

namespace App\Identity\User\Application\Recovery\SendEmail;

use App\Identity\User\Domain\Exceptions\UserNotFound;
use App\Identity\User\Domain\Services\UserByEmailFinder;
use App\Identity\User\Domain\UserEmail;
use App\Shared\Domain\MailProvider;
use App\Shared\Domain\ResetPasswordProvider;
use App\Shared\Domain\UserId;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Throwable;

class UserPasswordRecoveryEmailSender
{
    public function __construct(
        private ResetPasswordProvider $passwordResetter,
        private MailProvider $mailer,
        private UserByEmailFinder $finder
    ) {
    }

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

        $email = (new Email())
            ->from(new Address('no-reply@ticketplus.com', 'Ticketplus'))
            ->to($user->email()->value())
            ->subject('Recuperación de contraseña')
            ->html(
                sprintf('<p>Usa este código/token para recuperar tu contraseña:</p><p><strong>%s</strong></p>', $token)
            );

        $this->mailer->send($email);
    }
}
