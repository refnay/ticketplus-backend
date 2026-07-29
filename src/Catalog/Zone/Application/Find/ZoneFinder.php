<?php

namespace App\Catalog\Zone\Application\Find;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\Exceptions\EventDayNotFound;
use App\Catalog\Event\Domain\Services\EventFinder;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\Services\ZoneFinder as ServicesZoneFinder;
use App\Catalog\Zone\Domain\ZoneId;

class ZoneFinder
{
    public function __construct(private ServicesZoneFinder $zoneFinder, private EventFinder $eventFinder)
    {
    }

    public function __invoke(ZoneId $id, EventId $eventId, EventDayId $dayId, CompanyId $companyId): ZoneResponse
    {
        $event = $this->eventFinder->__invoke($eventId, $companyId);
        $day = $event->findDayById($dayId);

        if (is_null($day)) {
            throw new EventDayNotFound();
        }

        $zone = $this->zoneFinder->__invoke($id, $day->id());

        return ZoneResponse::create($zone);
    }
}
