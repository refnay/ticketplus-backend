<?php

namespace App\Account\User\Application\Recovery\Execute;

use App\Account\User\Domain\UserPassword;
use App\Shared\Domain\Services\PasswordHasher;

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