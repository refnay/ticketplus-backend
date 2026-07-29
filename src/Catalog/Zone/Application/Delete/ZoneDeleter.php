<?php

namespace App\Catalog\Zone\Application\Delete;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\Exceptions\EventDayNotFound;
use App\Catalog\Event\Domain\Services\EventFinder;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\Services\ZoneFinder;
use App\Catalog\Zone\Domain\ZoneId;
use App\Catalog\Zone\Domain\ZoneRepository;

class ZoneDeleter
{
    public function __construct(
        private ZoneRepository $repository,
        private EventFinder $eventFinder,
        private ZoneFinder $zoneFinder,
    ) {
    }

    public function __invoke(ZoneId $id, EventId $eventId, EventDayId $dayId, CompanyId $companyId): void
    {
        $event = $this->eventFinder->__invoke($eventId, $companyId);
        $day = $event->findDayById($dayId);

        if (is_null($day)) {
            throw new EventDayNotFound();
        }

        $zone = $this->zoneFinder->__invoke($id, $day->id());

        $this->repository->delete($zone);
    }
}
