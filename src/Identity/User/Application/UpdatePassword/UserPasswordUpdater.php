<?php

namespace App\Identity\User\Application\UpdatePassword;

use App\Identity\User\Domain\UserId;
use App\Identity\User\Domain\UserPassword;
use App\Identity\User\Domain\UserRepository;

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