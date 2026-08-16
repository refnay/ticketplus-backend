<?php

namespace App\Sale\Shared\Domain;

interface EventDayRepository
{
    public function findById(EventId $dayId, EventDayId $id): ?EventDay;
}