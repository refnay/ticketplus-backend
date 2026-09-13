<?php

namespace App\Catalog\Zone\Application\SearchAvailable;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\Services\EventByDayPublishedFinder;

class SearchAvailableZoneQueryHandler
{
    public function __construct(
        private EventByDayPublishedFinder $eventFinder,
        private ZoneSearcher $searcher,
    ) {}

    public function __invoke(SearchAvailableZoneQuery $query): ZonesResponse
    {
        $this->eventFinder->__invoke(EventDayId::fromString($query->day()));

        return $this->searcher->__invoke(
            $query->filters(),
            $query->orderBy(),
            $query->order(),
            $query->limit(),
            $query->offset(),
        );
    }
}
