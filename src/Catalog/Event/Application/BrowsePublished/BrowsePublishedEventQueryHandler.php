<?php

namespace App\Catalog\Event\Application\BrowsePublished;

class BrowsePublishedEventQueryHandler
{
    public function __construct(private EventSearcher $searcher) {}

    public function __invoke(BrowsePublishedEventQuery $query): EventsResponse
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
