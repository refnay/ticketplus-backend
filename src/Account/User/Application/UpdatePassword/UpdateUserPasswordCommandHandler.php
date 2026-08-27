<?php

namespace App\Account\User\Application\UpdatePassword;

use App\Shared\Application\Security\AuthorizationContext;
use App\Account\User\Domain\UserId;
use App\Account\User\Domain\UserPassword;
use App\Account\User\Application\Port\Hash\PasswordHasher;

class UpdateUserPasswordCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private UserPasswordUpdater $updater, private PasswordHasher $hasher)
    {
    }

    public function __invoke(UpdateUserPasswordCommand $command): void
    {
        $this->updater->__invoke(
            UserId::fromString($this->authorization->userId()),
            UserPassword::fromString($command->oldPassword()),
            UserPassword::fromString($this->hasher->hash($command->newPassword())),
        );
    }
}
