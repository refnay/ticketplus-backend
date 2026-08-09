<?php

namespace App\Account\CompanyMember\Application\Company\Find;

use App\Account\CompanyMember\Domain\CompanyMemberId;
use App\Account\Company\Domain\Services\CompanyFinder;
use App\Account\CompanyMember\Domain\Services\CompanyMemberFinder as ServicesCompanyMemberFinder;
use App\Account\User\Domain\Services\UserFinder;

class CompanyMemberFinder
{
    public function __construct(
        private UserFinder $userFinder,
        private CompanyFinder $companyFinder,
        private ServicesCompanyMemberFinder $companyMemberFinder,
    ) {}

    public function __invoke(CompanyMemberId $id): CompanyMemberResponse
    {
        $member = $this->companyMemberFinder->__invoke($id);
        
        $user = $this->userFinder->__invoke($member->userId());
        $company = $this->companyFinder->__invoke($member->companyId());

        return new CompanyMemberResponse(
            $user->id()->value(),
            $user->name()->value(),
            $user->lastName()->value(),
            $member->role()->value(),
            $member->status()->value(),
            $company->name()->value(),
        );
    }
}
