<?php

namespace App\Catalog\Zone\Domain\Services;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Zone\Domain\Exceptions\ZoneNotFound;
use App\Catalog\Zone\Domain\Zone;
use App\Catalog\Zone\Domain\ZoneId;
use App\Catalog\Zone\Domain\ZoneRepository;

class ZoneFinder
{
    public function __construct(private ZoneRepository $repository)
    {
    }

    public function __invoke(ZoneId $id, EventDayId $dayId): Zone
    {
        $zone = $this->repository->findById($id, $dayId);

        if (is_null($zone)) {
            throw new ZoneNotFound();
        }

        return $zone;
    }
}