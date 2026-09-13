<?php

namespace App\Account\User\Application\SwitchCompany;

use App\Account\Company\Domain\CompanyId;
use App\Account\Member\Domain\Exceptions\MemberNotAllowed;
use App\Account\Member\Domain\Services\MemberByUserAndCompanyFinder;
use App\Account\User\Domain\Services\UserFinder;
use App\Account\User\Domain\UserCurrentCompany;
use App\Account\User\Domain\UserId;
use App\Account\User\Domain\UserRepository;

class UserCompanySwitcher
{
    public function __construct(
        private UserRepository $repository,
        private UserFinder $userFinder,
        private MemberByUserAndCompanyFinder $memberFinder,
    ) {
    }

    public function __invoke(UserId $id, UserCurrentCompany $currentCompany): void
    {
        $user = $this->userFinder->__invoke($id);
        $member = $this->memberFinder->__invoke($id, CompanyId::fromString($currentCompany->value()));
        
        if ($member->status()->isInactive()) {
            throw new MemberNotAllowed();
        }

        $user->changeCurrentCompany($currentCompany);

        $this->repository->update($user);
    }
}
