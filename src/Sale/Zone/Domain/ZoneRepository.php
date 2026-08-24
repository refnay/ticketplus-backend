<?php

namespace App\Sale\Zone\Domain;

use App\Sale\EventDay\Domain\EventDayId;
use App\Sale\Event\Domain\EventId;

interface ZoneRepository
{
    public function findById(ZoneId $id, EventId $eventId, EventDayId $dayId): ?Zone;
}
