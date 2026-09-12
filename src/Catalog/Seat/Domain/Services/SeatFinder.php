<?php

namespace App\Catalog\Seat\Domain\Services;

use App\Catalog\Seat\Domain\Exceptions\SeatNotFound;
use App\Catalog\Seat\Domain\Seat;
use App\Catalog\Seat\Domain\SeatId;
use App\Catalog\Seat\Domain\SeatRepository;

class SeatFinder
{
    public function __construct(private SeatRepository $repository)
    {
    }

    public function __invoke(SeatId $id): Seat
    {
        $seat = $this->repository->find($id);

        if (is_null($seat)) {
            throw new SeatNotFound();
        }

        return $seat;
    }
}
