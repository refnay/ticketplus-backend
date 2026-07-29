<?php

namespace App\Catalog\Zone\Application\Update;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\Exceptions\EventDayNotFound;
use App\Catalog\Event\Domain\Services\EventFinder;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\Services\ZoneFinder;
use App\Catalog\Zone\Domain\ZoneCurrency;
use App\Catalog\Zone\Domain\ZoneHierarchy;
use App\Catalog\Zone\Domain\ZoneId;
use App\Catalog\Zone\Domain\ZoneName;
use App\Catalog\Zone\Domain\ZoneNumberedSeating;
use App\Catalog\Zone\Domain\ZonePrice;
use App\Catalog\Zone\Domain\ZoneQuantity;
use App\Catalog\Zone\Domain\ZoneRepository;
use App\Catalog\Zone\Domain\ZoneTaxRate;

class ZoneUpdater
{
    public function __construct(
        private ZoneRepository $repository,
        private EventFinder $eventFinder,
        private ZoneFinder $zoneFinder,
    ) {
    }

    public function __invoke(
        ZoneId $id,
        ZoneName $name,
        ZoneCurrency $currency,
        ZoneTaxRate $taxRate,
        ZonePrice $price,
        ZoneQuantity $quantity,
        ZoneHierarchy $hierarchy,
        ZoneNumberedSeating $numberedSeating,
        EventId $eventId,
        EventDayId $dayId,
        CompanyId $companyId
    ): void {
        $event = $this->eventFinder->__invoke($eventId, $companyId);
        $day = $event->findDayById($dayId);

        if (is_null($day)) {
            throw new EventDayNotFound();
        }

        $zone = $this->zoneFinder->__invoke($id, $day->id());
        
        $zone->changeName($name);
        $zone->changeCurrency($currency);
        $zone->changeTaxRate($taxRate);
        $zone->changePrice($price);
        $zone->changeQuantity($quantity);
        $zone->changeHierarchy($hierarchy);
        $zone->changeNumberedSeating($numberedSeating);

        $this->repository->update($zone);
    }
}
