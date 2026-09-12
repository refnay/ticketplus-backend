<?php

namespace App\Catalog\Seat\Application\OnPaymentApproved;

use App\Catalog\Seat\Domain\SeatId;
use App\Catalog\Seat\Domain\SeatRepository;
use App\Catalog\Seat\Domain\SeatStatus;
use App\Catalog\Seat\Domain\Services\SeatFinder;

class SeatUpdater
{
    public function __construct(private SeatRepository $repository, private SeatFinder $finder) {}

    public function __invoke(?array $seatIds): void
    {
        if (!(is_array($seatIds) && count($seatIds) > 0)) {
            return;
        }

        foreach ($seatIds as $seatId) {
            $seat = $this->finder->__invoke(SeatId::fromString($seatId));
            $seat->changeStatus(SeatStatus::sold());

            $this->repository->update($seat);
        }
    }
}
