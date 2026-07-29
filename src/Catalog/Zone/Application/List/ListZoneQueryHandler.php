<?php

namespace App\Catalog\Zone\Application\List;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Shared\Domain\CompanyId;

class ListZoneQueryHandler
{
    public function __construct(private ZoneLister $lister)
    {
    }

    public function __invoke(ListZoneQuery $query): ZonesResponse
    {
        return $this->lister->__invoke(
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