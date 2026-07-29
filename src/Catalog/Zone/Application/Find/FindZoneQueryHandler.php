<?php

namespace App\Catalog\Zone\Application\Find;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\ZoneId;

class FindZoneQueryHandler
{
    public function __construct(private ZoneFinder $finder)
    {
    }

    public function __invoke(FindZoneQuery $query): ZoneResponse
    {
        return $this->finder->__invoke(
            ZoneId::fromString($query->id()),
            EventId::fromString($query->event()),
            EventDayId::fromString($query->day()),
            CompanyId::fromString($query->session()->company()),
        );
    }
}