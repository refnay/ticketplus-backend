<?php

namespace App\Account\Member\Application\SearchUser;

use App\Shared\Application\Security\AuthorizationContext;

class SearchMemberUserQueryHandler
{
    public function __construct(private AuthorizationContext $authorization, private MemberUserSearcher $searcher) {}

    public function __invoke(SearchMemberUserQuery $query): MemberUsersResponse
    {
        return $this->searcher->__invoke(
            $query->filters($this->authorization->companyId()),
            $query->orderBy(),
            $query->order(),
            $query->limit(),
            $query->offset(),
        );
    }
}
