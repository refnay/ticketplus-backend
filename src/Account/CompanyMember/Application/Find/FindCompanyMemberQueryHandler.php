<?php

namespace App\Account\CompanyMember\Application\Company\Find;

use App\Account\CompanyMember\Domain\CompanyMemberId;

class FindCompanyMemberQueryHandler
{
    public function __construct(private CompanyMemberFinder $finder)
    {
    }

    public function __invoke(FindCompanyMemberQuery $query): CompanyMemberResponse
    {
        return $this->finder->__invoke(CompanyMemberId::fromString($query->session()->member()));
    }
}