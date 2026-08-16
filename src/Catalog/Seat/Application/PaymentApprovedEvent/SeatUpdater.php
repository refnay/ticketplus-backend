<?php

namespace App\Catalog\Seat\Application\PaymentApprovedEvent;

use App\Catalog\Seat\Domain\SeatId;
use App\Catalog\Seat\Domain\SeatRepository;
use App\Catalog\Seat\Domain\SeatStatus;
use App\Catalog\Seat\Domain\Services\SeatFinder;
use App\Catalog\Zone\Domain\ZoneId;

class SeatUpdater
{
    public function __construct(private SeatRepository $repository, private SeatFinder $finder) {}

    public function __invoke(ZoneId $zoneId, ?array $seatIds): void
    {
        if (is_null($seatIds)) {
            return;
        }

        foreach ($seatIds as $seatId) {
            $seat = $this->finder->__invoke(SeatId::fromString($seatId), $zoneId);

            $seat->changeStatus(SeatStatus::sold());
            
            $this->repository->update($seat);
        }
    }
}
