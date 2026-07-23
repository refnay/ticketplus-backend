<?php

namespace App\Identity\User\Application\Recovery\Execute;

use App\Identity\User\Domain\UserEmail;
use App\Identity\User\Domain\UserPassword;
use App\Shared\Domain\PasswordProvider;

class ExecuteUserPasswordRecoveryCommandHandler
{
    public function __construct(private UserPasswordRecoveryExecute $execute, private PasswordProvider $hasher)
    {
    }

    public function __invoke(ExecuteUserPasswordRecoveryCommand $command): void
    {
        $this->execute->__invoke(
            UserEmail::fromString($command->email()),
            UserPassword::fromString($this->hasher->hash($command->newPassword())),
            $command->token()
        );
    }
}