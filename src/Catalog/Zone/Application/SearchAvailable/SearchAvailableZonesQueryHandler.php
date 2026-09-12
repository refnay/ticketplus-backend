<?php

namespace App\Catalog\Zone\Application\SearchAvailable;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\Services\PublishedEventByDayFinder;
use App\Catalog\Zone\Domain\Zone;
use App\Catalog\Zone\Domain\ZoneRepository;

class SearchAvailableZonesQueryHandler
{
    public function __construct(
        private PublishedEventByDayFinder $eventFinder,
        private ZoneRepository $repository,
    ) {
    }

    public function __invoke(SearchAvailableZonesQuery $query): AvailableZonesResponse
    {
        $this->eventFinder->__invoke(EventDayId::fromString($query->day()));

        $zones = $this->repository->searchByFilters(
            $query->filters(),
            $query->orderBy(),
            $query->order(),
            $query->limit(),
            $query->offset(),
        );
        $total = $this->repository->countByFilters($query->filters());

        return new AvailableZonesResponse(
            $total,
            ...array_map(static fn(Zone $zone): AvailableZoneResponse => AvailableZoneResponse::create($zone), $zones),
        );
    }
}
