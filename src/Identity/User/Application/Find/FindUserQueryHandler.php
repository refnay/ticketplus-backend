<?php

namespace App\Identity\User\Application\Find;

use App\Identity\User\Domain\UserId;

class FindUserQueryHandler
{
    public function __construct(private UserFinder $finder)
    {
    }

    public function __invoke(FindUserQuery $query): UserResponse
    {
        return $this->finder->__invoke(UserId::fromString($query->session()->user()));
    }
}