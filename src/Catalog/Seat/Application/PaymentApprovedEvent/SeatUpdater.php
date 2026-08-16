<?php

namespace App\Catalog\Seat\Application\OrderReservedEvent;

use App\Catalog\Seat\Domain\SeatId;
use App\Catalog\Seat\Domain\SeatRepository;
use App\Catalog\Seat\Domain\SeatStatus;
use App\Catalog\Seat\Domain\Services\SeatFinder;
use App\Catalog\Zone\Domain\ZoneId;

class SeatUpdater
{
    public function __construct(private SeatRepository $repository, private SeatFinder $finder)
    {
    }

    public function __invoke(ZoneId $zoneId, array $seatIds): void
    {   
        foreach ($seatIds as $seatId) {
            $seat = $this->finder->__invoke(SeatId::fromString($seatId), $zoneId);

            if (!$seat->status()->isAvailable()) {
                continue;
            }

            $seat->changeStatus(SeatStatus::reserved());

            $this->repository->update($seat);
        }
    }
}
