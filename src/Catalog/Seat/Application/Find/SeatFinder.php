<?php

namespace App\Catalog\Seat\Application\Find;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\Exceptions\EventDayNotFound;
use App\Catalog\Event\Domain\Services\EventFinder;
use App\Catalog\Seat\Domain\SeatId;
use App\Catalog\Seat\Domain\Services\SeatFinder as ServicesSeatFinder;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\Services\ZoneFinder;
use App\Catalog\Zone\Domain\ZoneId;

class SeatFinder
{
    public function __construct(
        private ZoneFinder $zoneFinder,
        private EventFinder $eventFinder,
        private ServicesSeatFinder $seatFinder
    ) {
    }

    public function __invoke(SeatId $id, EventId $eventId, EventDayId $dayId, ZoneId $zoneId, CompanyId $companyId): SeatResponse
    {
        $event = $this->eventFinder->__invoke($eventId, $companyId);
        $day = $event->findDayById($dayId);

        if (is_null($day)) {
            throw new EventDayNotFound();
        }

        $zone = $this->zoneFinder->__invoke($zoneId, $day->id());
        $seat = $this->seatFinder->__invoke($id, $zone->id());

        return SeatResponse::create($seat);
    }
}
