<?php

namespace App\Sale\Shared\Domain;

use App\Sale\Shared\Domain\EventDayId;
use App\Sale\Shared\Domain\EventId;

interface ZoneRepository
{
    public function findById(ZoneId $id, EventId $eventId, EventDayId $dayId): ?Zone;
}