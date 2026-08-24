<?php

namespace App\Sale\Reference\Zone\Domain\Services;

use App\Sale\Reference\EventDay\Domain\EventDayId;
use App\Sale\Reference\Event\Domain\EventId;
use App\Sale\Reference\Zone\Domain\Exceptions\ZoneNotFound;
use App\Sale\Reference\Zone\Domain\Zone;
use App\Sale\Reference\Zone\Domain\ZoneId;
use App\Sale\Reference\Zone\Domain\ZoneRepository;

class ZoneFinder
{
    public function __construct(private ZoneRepository $repository)
    {
    }

    public function __invoke(ZoneId $id, EventId $eventId, EventDayId $dayId): Zone
    {
        $zone = $this->repository->findById($id, $eventId, $dayId);

        if (is_null($zone)) {
            throw new ZoneNotFound();
        }

        return $zone;
    }
}
