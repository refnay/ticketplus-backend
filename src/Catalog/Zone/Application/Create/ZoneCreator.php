<?php

namespace App\Catalog\Zone\Application\Create;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\Services\EventByDayFinder;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\Zone;
use App\Catalog\Zone\Domain\ZoneHierarchy;
use App\Catalog\Zone\Domain\ZoneName;
use App\Catalog\Zone\Domain\ZoneNumberedSeating;
use App\Catalog\Zone\Domain\ZonePrice;
use App\Catalog\Zone\Domain\ZoneQuantity;
use App\Catalog\Zone\Domain\ZoneRepository;

class ZoneCreator
{
    public function __construct(private ZoneRepository $repository, private EventByDayFinder $eventFinder)
    {
    }

    public function __invoke(
        ZoneName $name,
        ZonePrice $price,
        ZoneQuantity $quantity,
        ZoneHierarchy $hierarchy,
        ZoneNumberedSeating $numberedSeating,
        EventDayId $dayId,
        CompanyId $companyId
    ): string {
        $this->eventFinder->__invoke($dayId, $companyId);

        $zone = Zone::create(
            $name,
            $hierarchy,
            $numberedSeating,
            $price,
            $quantity,
            $dayId,
        );

        $this->repository->save($zone);

        return $zone->id()->value();
    }
}
