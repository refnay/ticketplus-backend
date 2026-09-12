<?php

namespace App\Account\User\Application\ExecutePasswordRecovery;

use App\Account\User\Domain\UserPassword;
use App\Account\User\Application\Port\Hash\PasswordHasher;

class ExecuteUserPasswordRecoveryCommandHandler
{
    public function __construct(private UserPasswordRecoveryExecute $execute, private PasswordHasher $hasher)
    {
    }

    public function __invoke(ExecuteUserPasswordRecoveryCommand $command): void
    {
        $this->execute->__invoke(
            UserPassword::fromString($this->hasher->hash($command->newPassword())),
            $command->token()
        );
    }
}
