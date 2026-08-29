<?php

namespace App\Account\Company\Application\Find;

use App\Account\Company\Domain\CompanyId;
use App\Account\Company\Domain\Services\CompanyFinder as DomainCompanyFinder;

class CompanyFinder
{
    public function __construct(private DomainCompanyFinder $finder)
    {
    }

    public function __invoke(CompanyId $id): CompanyResponse
    {
        return CompanyResponse::create($this->finder->__invoke($id));
    }
}
