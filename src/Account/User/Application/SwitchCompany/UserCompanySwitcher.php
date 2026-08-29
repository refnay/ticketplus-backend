<?php

namespace App\Account\User\Application\SwitchCompany;

use App\Account\Company\Domain\CompanyId;
use App\Account\Company\Domain\Services\CompanyFinder;
use App\Account\User\Domain\Services\UserFinder;
use App\Account\User\Domain\UserCurrentCompany;
use App\Account\User\Domain\UserId;
use App\Account\User\Domain\UserRepository;

class UserCompanySwitcher
{
    public function __construct(
        private UserRepository $repository,
        private UserFinder $userFinder,
        private CompanyFinder $companyFinder,
    ) {
    }

    public function __invoke(UserId $id, UserCurrentCompany $currentCompany): void
    {
        $user = $this->userFinder->__invoke($id);
        
        $this->companyFinder->__invoke(CompanyId::fromString($currentCompany->value()));

        $user->changeCurrentCompany($currentCompany);

        $this->repository->update($user);
    }
}