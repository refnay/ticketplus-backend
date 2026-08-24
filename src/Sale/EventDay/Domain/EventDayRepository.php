<?php

namespace App\Sale\EventDay\Domain;

use App\Sale\Event\Domain\EventId;

interface EventDayRepository
{
    public function findById(EventId $eventId, EventDayId $id): ?EventDay;
}
