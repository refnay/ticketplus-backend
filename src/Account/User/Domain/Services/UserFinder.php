<?php

namespace App\Account\User\Domain\Services;

use App\Account\User\Domain\Exceptions\UserNotFound;
use App\Account\User\Domain\User;
use App\Account\User\Domain\UserId;
use App\Account\User\Domain\UserRepository;

class UserFinder
{
    public function __construct(private UserRepository $repository)
    {
    }

    public function __invoke(UserId $id): User
    {
        $user = $this->repository->findById($id);

        if (is_null($user)) {
            throw new UserNotFound();
        }

        return $user;
    }
}