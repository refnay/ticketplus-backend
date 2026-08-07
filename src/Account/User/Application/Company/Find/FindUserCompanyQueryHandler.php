<?php

namespace App\Account\User\Application\Company\Find;

use App\Account\Company\Domain\CompanyId;
use App\Account\Company\Domain\CompanyMemberId;
use App\Account\User\Domain\UserId;

class FindUserCompanyQueryHandler
{
    public function __construct(private UserCompanyFinder $finder)
    {
    }

    public function __invoke(FindUserCompanyQuery $query): UserCompanyResponse
    {
        return $this->finder->__invoke(CompanyMemberId::fromString($query->session()->member()));
    }
}