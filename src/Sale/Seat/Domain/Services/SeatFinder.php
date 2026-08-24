<?php

namespace App\Sale\Seat\Domain\Services;

use App\Sale\Seat\Domain\Exceptions\SeatNotFound;
use App\Sale\Seat\Domain\Seat;
use App\Sale\Seat\Domain\SeatId;
use App\Sale\Seat\Domain\SeatRepository;
use App\Sale\Zone\Domain\ZoneId;

class SeatFinder
{
    public function __construct(private SeatRepository $repository)
    {
    }

    public function __invoke(SeatId $id, ZoneId $zoneId): Seat
    {
        $seat = $this->repository->findById($id, $zoneId);

        if (is_null($seat)) {
            throw new SeatNotFound();
        }

        return $seat;
    }
}
