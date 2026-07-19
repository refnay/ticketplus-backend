<?php

namespace App\Identity\User\Domain\Services;

use App\Identity\User\Domain\Exceptions\UserNotFound;
use App\Identity\User\Domain\User;
use App\Identity\User\Domain\UserEmail;
use App\Identity\User\Domain\UserRepository;

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