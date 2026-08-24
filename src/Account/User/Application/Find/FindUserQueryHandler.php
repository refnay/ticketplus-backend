<?php

namespace App\Account\User\Application\Find;

use App\Shared\Application\Security\AuthorizationContext;
use App\Account\User\Domain\UserId;

class FindUserQueryHandler
{
    public function __construct(private AuthorizationContext $authorization, private UserFinder $finder)
    {
    }

    public function __invoke(FindUserQuery $query): UserResponse
    {
        return $this->finder->__invoke(UserId::fromString($this->authorization->userId()));
    }
}