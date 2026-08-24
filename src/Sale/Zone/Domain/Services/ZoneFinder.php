<?php

namespace App\Sale\Zone\Domain\Services;

use App\Sale\EventDay\Domain\EventDayId;
use App\Sale\Event\Domain\EventId;
use App\Sale\Zone\Domain\Exceptions\ZoneNotFound;
use App\Sale\Zone\Domain\Zone;
use App\Sale\Zone\Domain\ZoneId;
use App\Sale\Zone\Domain\ZoneRepository;

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
