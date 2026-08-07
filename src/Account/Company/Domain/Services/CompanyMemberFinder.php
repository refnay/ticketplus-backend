<?php

namespace App\Account\Company\Domain\Services;

use App\Account\Company\Domain\CompanyMember;
use App\Account\Company\Domain\CompanyMemberId;
use App\Account\Company\Domain\CompanyMemberRepository;
use App\Account\Company\Domain\Exceptions\CompanyMemberNotFound;

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