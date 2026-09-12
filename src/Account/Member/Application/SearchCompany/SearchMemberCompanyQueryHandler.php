<?php

namespace App\Account\Member\Application\SearchCompany;

use App\Shared\Application\Security\AuthorizationContext;

class SearchMemberCompanyQueryHandler
{
    public function __construct(private AuthorizationContext $authorization, private MemberCompanySearcher $searcher)
    {
    }

    public function __invoke(SearchMemberCompanyQuery $query): MemberCompaniesResponse
    {
        $userId = $this->authorization->userId();

        return $this->searcher->__invoke(
            $query->filters($userId),
            $query->orderBy(),
            $query->order(),
            $query->limit(),
            $query->offset(),
        );
    }
}
