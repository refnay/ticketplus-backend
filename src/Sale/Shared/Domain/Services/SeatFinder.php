<?php

namespace App\Sale\Shared\Domain\Services;

use App\Sale\Shared\Domain\Exceptions\SeatNotFound;
use App\Sale\Shared\Domain\Seat;
use App\Sale\Shared\Domain\SeatId;
use App\Sale\Shared\Domain\SeatRepository;
use App\Sale\Shared\Domain\ZoneId;

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