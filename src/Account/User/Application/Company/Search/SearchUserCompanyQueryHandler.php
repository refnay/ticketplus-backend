<?php

namespace App\Account\User\Application\Company\Search;

class SearchUserCompanyQueryHandler
{
    public function __construct(private UserCompanySearcher $seacher)
    {
    }

    public function __invoke(SearchUserCompanyQuery $query): UserCompaniesResponse
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