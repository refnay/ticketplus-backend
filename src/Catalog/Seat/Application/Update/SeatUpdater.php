<?php

namespace App\Catalog\Seat\Application\Update;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\Exceptions\EventDayNotFound;
use App\Catalog\Event\Domain\Services\EventFinder;
use App\Catalog\Seat\Domain\Exceptions\SeatAlreadyExists;
use App\Catalog\Seat\Domain\Exceptions\SeatNotFound;
use App\Catalog\Seat\Domain\SeatCode;
use App\Catalog\Seat\Domain\SeatId;
use App\Catalog\Seat\Domain\SeatRepository;
use App\Catalog\Seat\Domain\SeatStatus;
use App\Catalog\Seat\Domain\Services\SeatByCodeFinder;
use App\Catalog\Seat\Domain\Services\SeatFinder;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\Services\ZoneFinder;
use App\Catalog\Zone\Domain\ZoneId;

class SeatUpdater
{
    public function __construct(
        private SeatRepository $repository,
        private EventFinder $eventFinder,
        private ZoneFinder $zoneFinder,
        private SeatFinder $seatFinder,
        private SeatByCodeFinder $seatByCodeFinder,
    ) {
    }

    public function __invoke(
        SeatId $id,
        SeatCode $code,
        SeatStatus $status,
        EventId $eventId,
        EventDayId $dayId,
        ZoneId $zoneId,
        CompanyId $companyId,
    ): void {
        $event = $this->eventFinder->__invoke($eventId, $companyId);
        $day = $event->findDayById($dayId);

        if (is_null($day)) {
            throw new EventDayNotFound();
        }

        $zone = $this->zoneFinder->__invoke($zoneId, $day->id());
        $seat = $this->seatFinder->__invoke($id, $zone->id());

        if (!$seat->status()->equals($status)) {
            try {
                $this->seatByCodeFinder->__invoke($code, $zone->id());
                throw new SeatAlreadyExists();
            } catch (SeatNotFound) {
            }
        }
        
        $seat->changeCode($code);
        $seat->changeStatus($status);

        $this->repository->update($seat);
    }
}
