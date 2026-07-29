<?php

namespace App\Catalog\Seat\Application\Create;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\Exceptions\EventDayNotFound;
use App\Catalog\Event\Domain\Services\EventFinder;
use App\Catalog\Seat\Domain\Exceptions\SeatAlreadyExists;
use App\Catalog\Seat\Domain\Exceptions\SeatNotFound;
use App\Catalog\Seat\Domain\Seat;
use App\Catalog\Seat\Domain\SeatCode;
use App\Catalog\Seat\Domain\SeatRepository;
use App\Catalog\Seat\Domain\Services\SeatByCodeFinder;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\Services\ZoneFinder;
use App\Catalog\Zone\Domain\ZoneId;

class SeatCreator
{
    public function __construct(
        private SeatRepository $repository,
        private EventFinder $eventFinder,
        private ZoneFinder $zoneFinder,
        private SeatByCodeFinder $seatFinder,
    ) {
    }

    public function __invoke(
        SeatCode $code,
        EventId $eventId,
        EventDayId $dayId,
        ZoneId $zoneId,
        CompanyId $companyId
    ): string {
        $event = $this->eventFinder->__invoke($eventId, $companyId);
        $day = $event->findDayById($dayId);

        if (is_null($day)) {
            throw new EventDayNotFound();
        }

        $zone = $this->zoneFinder->__invoke($zoneId, $day->id());

        try {
            $this->seatFinder->__invoke($code, $zone->id());
            throw new SeatAlreadyExists();
        } catch (SeatNotFound) {
        }

        $seat = Seat::create($code, $zone);

        $this->repository->save($seat);

        return $seat->id()->value();
    }
}
