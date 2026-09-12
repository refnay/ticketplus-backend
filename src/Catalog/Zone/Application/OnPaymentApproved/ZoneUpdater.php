<?php

namespace App\Catalog\Zone\Application\OnPaymentApproved;

use App\Catalog\Zone\Domain\Services\ZoneFinder;
use App\Catalog\Zone\Domain\ZoneId;
use App\Catalog\Zone\Domain\ZoneRepository;

class ZoneUpdater
{
    public function __construct(private ZoneRepository $repository, private ZoneFinder $finder)
    {
    }

    public function __invoke(ZoneId $id, int $quantity): void
    {
        $zone = $this->finder->__invoke($id);

        $reserved = $zone->quantity()->reserved() - $quantity;
        $sold = $zone->quantity()->sold() + $quantity;

        $zone->changeReservedQuantity($reserved);
        $zone->changeSoldQuantity($sold);

        $this->repository->update($zone);
    }
}
