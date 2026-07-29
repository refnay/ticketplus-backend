<?php

namespace App\Catalog\Seat\Application\List;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\ZoneId;

class ListSeatQueryHandler
{
    public function __construct(private SeatLister $lister)
    {
    }

    public function __invoke(ListSeatQuery $query): SeatsResponse
    {
        return $this->lister->__invoke(
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