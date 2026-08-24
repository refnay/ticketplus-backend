<?php

namespace App\Sale\Reference\User\Domain\Services;

use App\Sale\Reference\User\Domain\Exceptions\UserNotFound;
use App\Sale\Reference\User\Domain\User;
use App\Sale\Reference\User\Domain\UserId;
use App\Sale\Reference\User\Domain\UserRepository;

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
