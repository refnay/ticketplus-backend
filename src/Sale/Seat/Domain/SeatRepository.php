<?php

namespace App\Sale\Seat\Domain;

use App\Sale\Zone\Domain\ZoneId;

interface SeatRepository
{
    public function findById(SeatId $id, ZoneId $zoneId): ?Seat;
}
