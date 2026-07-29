<?php

namespace App\Catalog\Seat\Application\Delete;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\Exceptions\EventDayNotFound;
use App\Catalog\Event\Domain\Services\EventFinder;
use App\Catalog\Seat\Domain\SeatId;
use App\Catalog\Seat\Domain\SeatRepository;
use App\Catalog\Seat\Domain\Services\SeatFinder;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\Services\ZoneFinder;
use App\Catalog\Zone\Domain\ZoneId;

class SeatDeleter
{
    public function __construct(
        private SeatRepository $repository,
        private EventFinder $eventFinder,
        private ZoneFinder $zoneFinder,
        private SeatFinder $seatFinder,
    ) {
    }

    public function __invoke(SeatId $id, EventId $eventId, EventDayId $dayId, ZoneId $zoneId, CompanyId $companyId): void
    {
        $event = $this->eventFinder->__invoke($eventId, $companyId);
        $day = $event->findDayById($dayId);

        if (is_null($day)) {
            throw new EventDayNotFound();
        }

        $zone = $this->zoneFinder->__invoke($zoneId, $day->id());
        $seat = $this->seatFinder->__invoke($id, $zone->id());

        $this->repository->delete($seat);
    }
}
