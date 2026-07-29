<?php

namespace App\Catalog\Seat\Domain\Services;

use App\Catalog\Seat\Domain\Exceptions\SeatNotFound;
use App\Catalog\Seat\Domain\Seat;
use App\Catalog\Seat\Domain\SeatId;
use App\Catalog\Seat\Domain\SeatRepository;
use App\Catalog\Zone\Domain\ZoneId;

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