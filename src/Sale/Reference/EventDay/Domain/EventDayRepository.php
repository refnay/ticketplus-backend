<?php

namespace App\Sale\Reference\EventDay\Domain;

interface EventDayRepository
{
    public function findById(EventDayId $id): ?EventDay;
}
