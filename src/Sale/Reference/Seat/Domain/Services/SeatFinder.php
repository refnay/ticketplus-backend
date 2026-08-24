<?php

namespace App\Sale\Reference\Seat\Domain\Services;

use App\Sale\Reference\Seat\Domain\Exceptions\SeatNotFound;
use App\Sale\Reference\Seat\Domain\Seat;
use App\Sale\Reference\Seat\Domain\SeatId;
use App\Sale\Reference\Seat\Domain\SeatRepository;
use App\Sale\Reference\Zone\Domain\ZoneId;

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
