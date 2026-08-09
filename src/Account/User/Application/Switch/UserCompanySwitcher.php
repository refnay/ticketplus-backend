<?php

namespace App\Account\User\Application\Company\Switch;

use App\Account\User\Domain\Services\UserFinder;
use App\Account\User\Domain\UserCurrentCompany;
use App\Account\User\Domain\UserId;
use App\Account\User\Domain\UserRepository;

class UserCompanySwitcher
{
    public function __construct(private UserRepository $repository, private UserFinder $finder)
    {
    }

    public function __invoke(UserId $id, UserCurrentCompany $currentCompany): void
    {
        $user = $this->finder->__invoke($id);

        $user->changeCurrentCompany($currentCompany);

        $this->repository->update($user);
    }
}