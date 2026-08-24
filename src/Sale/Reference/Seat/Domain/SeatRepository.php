<?php

namespace App\Sale\Reference\Seat\Domain;

use App\Sale\Reference\Zone\Domain\ZoneId;

interface SeatRepository
{
    public function findById(SeatId $id, ZoneId $zoneId): ?Seat;
}
