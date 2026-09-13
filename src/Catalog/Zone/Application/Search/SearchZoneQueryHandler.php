<?php

namespace App\Catalog\Zone\Application\Search;

use App\Shared\Application\Security\AuthorizationContext;

class SearchZoneQueryHandler
{
    public function __construct(private AuthorizationContext $authorization, private ZoneSearcher $searcher) {}

    public function __invoke(SearchZoneQuery $query): ZonesResponse
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
