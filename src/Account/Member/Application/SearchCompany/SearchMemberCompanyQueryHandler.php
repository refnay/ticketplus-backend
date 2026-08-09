<?php

namespace App\Account\Member\Application\SearchCompany;

class SearchMemberCompanyQueryHandler
{
    public function __construct(private MemberCompanySearcher $seacher)
    {
    }

    public function __invoke(SearchMemberCompanyQuery $query): MemberCompaniesResponse
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