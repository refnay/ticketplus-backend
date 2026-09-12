<?php

namespace App\Account\User\Application\SwitchCompany;

use App\Account\Company\Domain\CompanyId;
use App\Account\Company\Domain\Services\CompanyFinder;
use App\Account\Member\Domain\Exceptions\MemberNotFound;
use App\Account\Member\Domain\MemberRepository;
use App\Account\Member\Domain\MemberStatusList;
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
        private MemberRepository $memberRepository,
    ) {
    }

    public function __invoke(UserId $id, UserCurrentCompany $currentCompany): void
    {
        $user = $this->userFinder->__invoke($id);

        $companyId = CompanyId::fromString($currentCompany->value());
        $this->companyFinder->__invoke($companyId);

        $membership = $this->memberRepository->findByUserAndCompany($id, $companyId);
        if (is_null($membership) || $membership->status()->value() !== MemberStatusList::ACTIVE->value) {
            throw new MemberNotFound();
        }

        $user->changeCurrentCompany($currentCompany);

        $this->repository->update($user);
    }
}
