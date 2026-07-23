<?php

namespace App\Identity\User\Application\UpdatePassword;

use App\Identity\User\Domain\UserId;
use App\Identity\User\Domain\UserPassword;
use App\Shared\Domain\PasswordProvider;

class UpdateUserPasswordCommandHandler
{
    public function __construct(private UserPasswordUpdater $updater, private PasswordProvider $hasher)
    {
    }

    public function __invoke(UpdateUserPasswordCommand $command): void
    {
        $this->updater->__invoke(
            UserId::fromString($command->session()->user()),
            UserPassword::fromString($this->hasher->hash($command->oldPassword())),
            UserPassword::fromString($this->hasher->hash($command->newPassword())),
        );
    }
}