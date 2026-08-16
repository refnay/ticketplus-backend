<?php

namespace App\Catalog\Zone\Application\PaymentApprovedEvent;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Zone\Domain\Services\ZoneFinder;
use App\Catalog\Zone\Domain\ZoneId;
use App\Catalog\Zone\Domain\ZoneRepository;

class ZoneUpdater
{
    public function __construct(private ZoneRepository $repository, private ZoneFinder $finder)
    {
    }

    public function __invoke(EventDayId $dayId, ZoneId $id, int $quantity): void
    {
        $zone = $this->finder->__invoke($id, $dayId);

        $reserved = $zone->quantity()->reserved() - $quantity;
        $sold = $zone->quantity()->sold() + $quantity;

        $zone->changeReservedQuantity($reserved);
        $zone->changeSoldQuantity($sold);
        
        $this->repository->update($zone);
    }
}
