<?php

namespace App\Identity\User\Application\Recovery\SendEmail;

use App\Identity\User\Domain\Exceptions\UserNotFound;
use App\Identity\User\Domain\Services\UserByEmailFinder;
use App\Identity\User\Domain\UserEmail;
use App\Identity\User\Domain\UserRepository;

class UserPasswordRecoveryEmailSender
{
    public function __construct(private UserRepository $repository, private UserByEmailFinder $finder)
    {
    }

    public function __invoke(UserEmail $email): void
    {
        try {
            $user = $this->finder->__invoke($email);
        } catch (UserNotFound) {
            return;
        }


    }
}