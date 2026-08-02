<?php

namespace App\Account\User\Application\Company\Find;

use App\Account\Company\Domain\CompanyId;
use App\Account\Company\Domain\Exceptions\CompanyMemberNotFound;
use App\Account\Company\Domain\Services\CompanyFinder;
use App\Account\User\Domain\Services\UserFinder as ServicesUserFinder;
use App\Account\User\Domain\UserId;

class UserCompanyFinder
{
    public function __construct(private ServicesUserFinder $userFinder, private CompanyFinder $companyFinder)
    {
    }

    public function __invoke(UserId $id, CompanyId $companyId): UserCompanyResponse
    {
        $user = $this->userFinder->__invoke($id);
        $company = $this->companyFinder->__invoke($companyId);

        foreach ($company->members() as $member) {
            if ($member->userId()->equals($user->id())) {
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
        
        throw new CompanyMemberNotFound();
    }
}