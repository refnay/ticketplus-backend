<?php

namespace App\Account\User\Application\Recovery\SendEmail;

use App\Account\User\Domain\UserEmail;

class SendUserPasswordRecoveryEmailCommandHandler
{
    public function __construct(private UserPasswordRecoveryEmailSender $sender)
    {
    }

    public function __invoke(SendUserPasswordRecoveryEmailCommand $command): void
    {
        $this->sender->__invoke(UserEmail::fromString($command->email()));
    }
}