<?php

namespace App\Account\CompanyMember\Domain\Services;

use App\Account\CompanyMember\Domain\CompanyMember;
use App\Account\CompanyMember\Domain\CompanyMemberId;
use App\Account\CompanyMember\Domain\CompanyMemberRepository;
use App\Account\CompanyMember\Domain\Exceptions\CompanyMemberNotFound;

class CompanyMemberFinder
{
    public function __construct(private CompanyMemberRepository $repository)
    {
    }

    public function __invoke(CompanyMemberId $id): CompanyMember
    {
        $companyMember = $this->repository->findById($id);

        if (is_null($companyMember)) {
            throw new CompanyMemberNotFound();
        }

        return $companyMember;
    }
}