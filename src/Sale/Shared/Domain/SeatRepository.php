<?php

namespace App\Sale\Shared\Domain;

interface SeatRepository
{
    public function findById(SeatId $id, ZoneId $zoneId): ?Seat;
}