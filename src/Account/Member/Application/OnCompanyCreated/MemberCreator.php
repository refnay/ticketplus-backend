<?php

namespace App\Account\Member\Application\OnCompanyCreated;

use App\Account\Company\Domain\CompanyId;
use App\Account\Member\Domain\Member;
use App\Account\Member\Domain\MemberRepository;
use App\Account\Member\Domain\MemberRole;
use App\Account\User\Domain\UserId;

class MemberCreator
{
    public function __construct(private MemberRepository $repository)
    {
    }

    public function __invoke(UserId $userId, CompanyId $companyId): void
    {
        $member = Member::create(MemberRole::owner(), $userId, $companyId);

        $this->repository->save($member);
    }
}
