<?php

namespace App\Sale\Shared\Domain\Services;

use App\Sale\Shared\Domain\EventDayId;
use App\Sale\Shared\Domain\EventId;
use App\Sale\Shared\Domain\Exceptions\ZoneNotFound;
use App\Sale\Shared\Domain\Zone;
use App\Sale\Shared\Domain\ZoneId;
use App\Sale\Shared\Domain\ZoneRepository;

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