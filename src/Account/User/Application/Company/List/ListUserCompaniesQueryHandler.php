<?php

namespace App\Account\User\Application\Company\List;

class ListUserCompaniesQueryHandler
{
    public function __construct(private UserCompaniesLister $lister)
    {
    }

    public function __invoke(ListUserCompaniesQuery $query): UserCompaniesResponse
    {
        return $this->lister->__invoke(
            $query->filters(),
            $query->orderBy(),
            $query->order(),
            $query->limit(),
            $query->offset(),
        );
    }
}