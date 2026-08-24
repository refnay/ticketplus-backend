<?php

namespace App\Sale\Reference\Zone\Domain;

use App\Sale\Reference\EventDay\Domain\EventDayId;
use App\Sale\Reference\Event\Domain\EventId;

interface ZoneRepository
{
    public function findById(ZoneId $id, EventId $eventId, EventDayId $dayId): ?Zone;
}
