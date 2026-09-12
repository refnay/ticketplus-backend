<?php

namespace App\Sale\Reference\Seat\Domain;

interface SeatRepository
{
    public function findById(SeatId $id): ?Seat;
}
