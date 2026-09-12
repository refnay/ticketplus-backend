<?php

namespace App\Sale\Reference\Seat\Domain\Services;

use App\Sale\Reference\Seat\Domain\Exceptions\SeatNotFound;
use App\Sale\Reference\Seat\Domain\Seat;
use App\Sale\Reference\Seat\Domain\SeatId;
use App\Sale\Reference\Seat\Domain\SeatRepository;

class SeatFinder
{
    public function __construct(private SeatRepository $repository)
    {
    }

    public function __invoke(SeatId $id): Seat
    {
        $seat = $this->repository->findById($id);

        if (is_null($seat)) {
            throw new SeatNotFound();
        }

        return $seat;
    }
}
