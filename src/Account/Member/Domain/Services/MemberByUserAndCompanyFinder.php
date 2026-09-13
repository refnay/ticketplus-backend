<?php

namespace App\Account\Member\Domain\Services;

use App\Account\Company\Domain\CompanyId;
use App\Account\Member\Domain\Member;
use App\Account\Member\Domain\MemberRepository;
use App\Account\Member\Domain\Exceptions\MemberNotFound;
use App\Account\User\Domain\UserId;

class MemberByUserAndCompanyFinder
{
    public function __construct(private MemberRepository $repository)
    {
    }

    public function __invoke(UserId $userId, CompanyId $companyId): Member
    {
        $member = $this->repository->findByUserAndCompany($userId, $companyId);

        if (is_null($member)) {
            throw new MemberNotFound();
        }

        return $member;
    }
}