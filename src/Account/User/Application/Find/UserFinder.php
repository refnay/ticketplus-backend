<?php

namespace App\Account\User\Application\Find;

use App\Account\User\Domain\Services\UserFinder as ServicesUserFinder;
use App\Account\User\Domain\UserId;

class UserFinder
{
    public function __construct(private ServicesUserFinder $finder)
    {
    }

    public function __invoke(UserId $id): UserResponse
    {
        $user = $this->finder->__invoke($id);
        
        return new UserResponse(
            $user->id()->value(),
            $user->email()->value(),
            $user->name()->value(),
            $user->lastName()->value(),
            $user->birthDate()->asDMY(),
            $user->city()->value(),
            $user->country()->value(),
            $user->document()->toArray(),
            $user->mobile()?->value(),
            $user->profileImage()?->value(),
        );
    }
}