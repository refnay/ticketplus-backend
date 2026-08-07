<?php

namespace App\Account\User\Application\Company\Find;

use App\Account\Company\Domain\CompanyMemberId;
use App\Account\Company\Domain\Services\CompanyFinder;
use App\Account\Company\Domain\Services\CompanyMemberFinder;
use App\Account\User\Domain\Services\UserFinder as ServicesUserFinder;

class UserCompanyFinder
{
    public function __construct(
        private ServicesUserFinder $userFinder,
        private CompanyFinder $companyFinder,
        private CompanyMemberFinder $companyMemberFinder,
    ) {}

    public function __invoke(CompanyMemberId $id): UserCompanyResponse
    {
        $member = $this->companyMemberFinder->__invoke($id);
        
        $user = $this->userFinder->__invoke($member->userId());
        $company = $this->companyFinder->__invoke($member->companyId());

        return new UserCompanyResponse(
            $user->id()->value(),
            $user->name()->value(),
            $user->lastName()->value(),
            $member->role()->value(),
            $member->status()->value(),
            $company->name()->value(),
        );
    }
}
