<?php

namespace App\Catalog\Event\Application\BrowsePublished;

class BrowsePublishedEventsQueryHandler
{
    public function __construct(private PublishedEventSearcher $searcher)
    {
    }

    public function __invoke(BrowsePublishedEventsQuery $query): PublishedEventsResponse
    {
        return $this->searcher->__invoke($query);
    }
}
