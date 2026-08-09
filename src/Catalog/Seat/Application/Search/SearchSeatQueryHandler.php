<?php

namespace App\Catalog\Seat\Application\Search;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\ZoneId;

class SearchSeatQueryHandler
{
    public function __construct(private SeatSearcher $searcher)
    {
    }

    public function __invoke(SearchSeatQuery $query): SeatsResponse
    {
        return $this->searcher->__invoke(
            EventId::fromString($query->event()),
            EventDayId::fromString($query->day()),
            ZoneId::fromString($query->zone()),
            CompanyId::fromString($query->company()),
            $query->filters(),
            $query->orderBy(),
            $query->order(),
            $query->limit(),
            $query->offset(),
        );
    }
}