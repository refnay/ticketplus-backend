<?php

namespace App\Catalog\Zone\Application\Search;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Shared\Domain\CompanyId;

class SearchZoneQueryHandler
{
    public function __construct(private ZoneSearcher $searcher)
    {
    }

    public function __invoke(SearchZoneQuery $query): ZonesResponse
    {
        return $this->searcher->__invoke(
            EventId::fromString($query->event()),
            EventDayId::fromString($query->day()),
            CompanyId::fromString($query->company()),
            $query->filters(),
            $query->orderBy(),
            $query->order(),
            $query->limit(),
            $query->offset(),
        );
    }
}