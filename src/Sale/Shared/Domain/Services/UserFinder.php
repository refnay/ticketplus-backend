<?php

namespace App\Sale\Shared\Domain\Services;

use App\Sale\Shared\Domain\Exceptions\UserNotFound;
use App\Sale\Shared\Domain\User;
use App\Sale\Shared\Domain\UserId;
use App\Sale\Shared\Domain\UserRepository;

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