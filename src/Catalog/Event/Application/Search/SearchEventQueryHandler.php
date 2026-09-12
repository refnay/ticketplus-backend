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
        $companyId = $this->authorization->companyId();

        return $this->searcher->__invoke(
            $query->filters($companyId),
            $query->orderBy(),
            $query->order(),
            $query->limit(),
            $query->offset(),
        );
    }
}
