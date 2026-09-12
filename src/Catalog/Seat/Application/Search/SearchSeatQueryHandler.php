<?php

namespace App\Catalog\Seat\Application\Search;

use App\Shared\Application\Security\AuthorizationContext;

class SearchSeatQueryHandler
{
    public function __construct(private AuthorizationContext $authorization, private SeatSearcher $searcher)
    {
    }

    public function __invoke(SearchSeatQuery $query): SeatsResponse
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
