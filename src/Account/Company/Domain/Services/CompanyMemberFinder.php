<?php

namespace App\Account\Company\Domain\Services;

use App\Account\Company\Domain\CompanyId;
use App\Account\Company\Domain\CompanyMember;
use App\Account\Company\Domain\CompanyMemberRepository;
use App\Account\Company\Domain\Exceptions\CompanyMemberNotFound;
use App\Account\User\Domain\UserId;

class CompanyMemberFinder
{
    public function __construct(private CompanyMemberRepository $repository)
    {
    }

    public function __invoke(UserId $userId, CompanyId $companyId): CompanyMember
    {
        $companyMember = $this->repository->findByUserAndCompany($userId, $companyId);

        if (is_null($companyMember)) {
            throw new CompanyMemberNotFound();
        }

        return $companyMember;
    }
}