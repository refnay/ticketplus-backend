<?php

namespace App\Account\User\Application\UpdatePassword;

use App\Account\User\Domain\UserId;
use App\Account\User\Domain\UserPassword;
use App\Account\User\Domain\UserRepository;

class UserPasswordUpdater
{
    public function __construct(private UserRepository $repository)
    {
    }

    public function __invoke(UserId $id, UserPassword $oldPassword, UserPassword $newPassword): void
    {
        $this->repository->updatePassword($id, $oldPassword, $newPassword);
    }
}