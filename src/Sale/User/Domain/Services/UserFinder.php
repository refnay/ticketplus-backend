<?php

namespace App\Sale\User\Domain\Services;

use App\Sale\User\Domain\Exceptions\UserNotFound;
use App\Sale\User\Domain\User;
use App\Sale\User\Domain\UserId;
use App\Sale\User\Domain\UserRepository;

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
