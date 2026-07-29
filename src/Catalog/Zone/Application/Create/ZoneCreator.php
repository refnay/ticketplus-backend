<?php

namespace App\Catalog\Zone\Application\Create;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\Exceptions\EventDayNotFound;
use App\Catalog\Event\Domain\Services\EventFinder;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\Zone;
use App\Catalog\Zone\Domain\ZoneCurrency;
use App\Catalog\Zone\Domain\ZoneHierarchy;
use App\Catalog\Zone\Domain\ZoneName;
use App\Catalog\Zone\Domain\ZoneNumberedSeating;
use App\Catalog\Zone\Domain\ZonePrice;
use App\Catalog\Zone\Domain\ZoneQuantity;
use App\Catalog\Zone\Domain\ZoneRepository;
use App\Catalog\Zone\Domain\ZoneTaxRate;

class ZoneCreator
{
    public function __construct(private ZoneRepository $repository, private EventFinder $eventFinder)
    {
    }

    public function __invoke(
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
    ): string {
        $event = $this->eventFinder->__invoke($eventId, $companyId);
        $day = $event->findDayById($dayId);

        if (is_null($day)) {
            throw new EventDayNotFound();
        }

        $zone = Zone::create(
            $name,
            $currency,
            $hierarchy,
            $numberedSeating,
            $price,
            $quantity,
            $taxRate,
            $day,
        );

        $this->repository->save($zone);

        return $zone->id()->value();
    }
}
