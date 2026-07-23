<?php

namespace App\Identity\User\Application\Recovery\Execute;

use App\Identity\User\Domain\Exceptions\UserNotFound;
use App\Identity\User\Domain\Services\UserByEmailFinder;
use App\Identity\User\Domain\UserEmail;
use App\Identity\User\Domain\UserPassword;
use App\Identity\User\Domain\UserRepository;
use App\Shared\Domain\ResetPasswordProvider;
use Throwable;

class UserPasswordRecoveryExecute
{
    public function __construct(
        private ResetPasswordProvider $passwordResetter,
        private UserRepository $repository,
        private UserByEmailFinder $finder
    ) {
    }

    public function __invoke(UserEmail $email, UserPassword $newPassword, string $token): void
    {
        try {
            $user = $this->finder->__invoke($email);
        } catch (UserNotFound) {
            return;
        }

        try {
            $this->passwordResetter->validateToken($token);
        } catch (Throwable) {
            return;
        }

        $this->passwordResetter->removeToken($token);
        
        $this->repository->resetPassword($user->id(), $newPassword);
    }
}
