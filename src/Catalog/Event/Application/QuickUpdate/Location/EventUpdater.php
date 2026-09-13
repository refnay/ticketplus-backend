<?php

namespace App\Catalog\Event\Application\QuickUpdate\Location;

use App\Catalog\Event\Domain\EventCity;
use App\Catalog\Event\Domain\EventCoordinates;
use App\Catalog\Event\Domain\EventCountry;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\EventLocation;
use App\Catalog\Event\Domain\EventRepository;
use App\Catalog\Event\Domain\EventVenue;
use App\Catalog\Event\Domain\Services\EventFinder;
use App\Catalog\Shared\Domain\CompanyId;

class EventUpdater
{
    public function __construct(private EventRepository $repository, private EventFinder $eventFinder) {}

    public function __invoke(
        EventId $id,
        EventVenue $venue,
        EventLocation $location,
        EventCountry $country,
        EventCity $city,
        EventCoordinates $coordinates,
        CompanyId $companyId,
    ): void {
        $event = $this->eventFinder->__invoke($id, $companyId);

        $event->changeVenue($venue);
        $event->changeLocation($location);
        $event->changeCountry($country);
        $event->changeCity($city);
        $event->changeCoordinates($coordinates);

        $this->repository->update($event);
    }
}
