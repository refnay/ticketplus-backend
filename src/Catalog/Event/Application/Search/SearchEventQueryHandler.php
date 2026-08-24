<?php

namespace App\Catalog\Event\Application\Search;

use App\Shared\Application\Security\AuthorizationContext;

class SearchEventQueryHandler
{
    public function __construct(private AuthorizationContext $authorization, private EventSearcher $searcher)
    {
    }

    public function __invoke(SearchEventQuery $query): EventsResponse
    {
        $this->authorization->requireAllPermissions();

        return $this->searcher->__invoke(
            $query->filters($this->authorization->requireCompanyId()),
            $query->orderBy(),
            $query->order(),
            $query->limit(),
            $query->offset(),
        );
    }
}
