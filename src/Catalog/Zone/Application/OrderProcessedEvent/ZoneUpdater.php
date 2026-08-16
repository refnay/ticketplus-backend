<?php

namespace App\Catalog\Zone\Application\OrderProcessedEvent;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Shared\Domain\OrderStatusList;
use App\Catalog\Zone\Domain\Services\ZoneFinder;
use App\Catalog\Zone\Domain\ZoneId;
use App\Catalog\Zone\Domain\ZoneRepository;

class ZoneUpdater
{
    public function __construct(private ZoneRepository $repository, private ZoneFinder $finder)
    {
    }

    public function __invoke(EventDayId $dayId, ZoneId $id, int $quantity, int $status): void
    {
        $zone = $this->finder->__invoke($id, $dayId);
        $reserved = $zone->quantity()->reserved();

        switch ($status) {
            case OrderStatusList::PENDING->value:
                $reserved = $zone->quantity()->reserved() + $quantity;
                break;
            case OrderStatusList::EXPIRED->value:
                $reserved = $zone->quantity()->reserved() - $quantity;
                break;
            default:
                return;
        }
        
        $zone->changeReservedQuantity($reserved);
        
        $this->repository->update($zone);
    }
}
