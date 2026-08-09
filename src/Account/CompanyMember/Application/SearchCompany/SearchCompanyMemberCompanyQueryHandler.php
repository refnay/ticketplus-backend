<?php

namespace App\Account\CompanyMember\Application\Company\SearchCompany;

class SearchCompanyMemberCompanyQueryHandler
{
    public function __construct(private CompanyMemberCompanySearcher $seacher)
    {
    }

    public function __invoke(SearchCompanyMemberCompanyQuery $query): CompanyMemberCompaniesResponse
    {
        return $this->seacher->__invoke(
            $query->filters(),
            $query->orderBy(),
            $query->order(),
            $query->limit(),
            $query->offset(),
        );
    }
}