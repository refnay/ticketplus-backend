<?php

namespace App\Identity\User\Domain\Services;

use App\Identity\User\Domain\Exceptions\UserNotFound;
use App\Identity\User\Domain\User;
use App\Identity\User\Domain\UserId;
use App\Identity\User\Domain\UserRepository;

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