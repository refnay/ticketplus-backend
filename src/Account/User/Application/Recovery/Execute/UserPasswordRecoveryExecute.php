<?php

namespace App\Account\User\Application\Recovery\Execute;

use App\Account\User\Domain\UserId;
use App\Account\User\Domain\UserPassword;
use App\Account\User\Domain\UserRepository;
use App\Shared\Domain\Exceptions\ExpiredOrInvalidResetToken;
use App\Shared\Domain\Services\PasswordResetter;
use Throwable;

class UserPasswordRecoveryExecute
{
    public function __construct(private PasswordResetter $passwordResetter, private UserRepository $repository)
    {
    }

    public function __invoke(UserPassword $newPassword, string $token): void
    {
        try {
            $id = $this->passwordResetter->validateToken($token);
        } catch (Throwable) {
            throw new ExpiredOrInvalidResetToken();
        }

        $this->passwordResetter->removeToken($token);
        
        $this->repository->resetPassword(UserId::fromString($id), $newPassword);
    }
}
