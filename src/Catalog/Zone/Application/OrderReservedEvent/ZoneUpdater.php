<?php

namespace App\Catalog\Zone\Application\OrderReservedEvent;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Zone\Domain\Services\ZoneFinder;
use App\Catalog\Zone\Domain\ZoneId;
use App\Shared\Infrastructure\Persistence\Repository\ZoneRepository;

class ZoneUpdater
{
    public function __construct(private ZoneRepository $repository, private ZoneFinder $finder)
    {
    }

    public function __invoke(EventDayId $dayId, ZoneId $id, int $quantity): void
    {
        $zone = $this->finder->__invoke($id, $dayId);
    }
}
