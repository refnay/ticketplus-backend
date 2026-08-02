<?php

namespace App\Catalog\Event\Application\Search;

class SearchEventQueryHandler
{
    public function __construct(private EventSearcher $searcher)
    {
    }

    public function __invoke(SearchEventQuery $query): EventsResponse
    {
        return $this->searcher->__invoke(
            $query->filters(),
            $query->orderBy(),
            $query->order(),
            $query->limit(),
            $query->offset(),
        );
    }
}