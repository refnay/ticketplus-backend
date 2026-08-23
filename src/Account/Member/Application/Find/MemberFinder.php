<?php

namespace App\Account\Member\Application\Find;

use App\Account\Member\Domain\MemberId;
use App\Account\Company\Domain\Services\CompanyFinder;
use App\Account\Member\Domain\Services\MemberFinder as ServicesMemberFinder;
use App\Account\User\Domain\Services\UserFinder;

class MemberFinder
{
    public function __construct(
        private UserFinder $userFinder,
        private CompanyFinder $companyFinder,
        private ServicesMemberFinder $memberFinder,
    ) {}

    public function __invoke(MemberId $id): MemberResponse
    {
        $member = $this->memberFinder->__invoke($id);
        $user = $this->userFinder->__invoke($member->userId());
        $company = $this->companyFinder->__invoke($member->companyId());

        return new MemberResponse(
            $member->id()->value(),
            $user->id()->value(),
            $user->name()->value(),
            $company->id()->value(),
            $company->name()->value(),
            $member->role()->value(),
            $member->status()->value(),
        );
    }
}
