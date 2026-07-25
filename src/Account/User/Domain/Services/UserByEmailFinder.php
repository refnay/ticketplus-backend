<?php

namespace App\Account\User\Domain\Services;

use App\Account\User\Domain\Exceptions\UserNotFound;
use App\Account\User\Domain\User;
use App\Account\User\Domain\UserEmail;
use App\Account\User\Domain\UserRepository;

class UserByEmailFinder
{
    public function __construct(private UserRepository $repository)
    {
    }

    public function __invoke(UserEmail $email): User
    {
        $user = $this->repository->findByEmail($email);

        if (is_null($user)) {
            throw new UserNotFound();
        }

        return $user;
    }
}