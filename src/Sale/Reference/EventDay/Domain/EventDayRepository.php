<?php

namespace App\Sale\Reference\EventDay\Domain;

use App\Sale\Reference\Event\Domain\EventId;

interface EventDayRepository
{
    public function findById(EventId $eventId, EventDayId $id): ?EventDay;
}
