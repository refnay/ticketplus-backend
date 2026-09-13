<?php

namespace App\Catalog\Seat\Application\SearchAvailable;

use App\Catalog\Zone\Domain\Services\ZonePublishedFinder;
use App\Catalog\Zone\Domain\ZoneId;

class SearchAvailableSeatQueryHandler
{
    public function __construct(
        private ZonePublishedFinder $zoneFinder,
        private SeatSearcher $searcher,
    ) {}

    public function __invoke(SearchAvailableSeatQuery $query): SeatsResponse
    {
        $this->zoneFinder->__invoke(ZoneId::fromString($query->zone()));

        return $this->searcher->__invoke(
            $query->filters(),
            $query->orderBy(),
            $query->order(),
            $query->limit(),
            $query->offset(),
        );
    }
}
