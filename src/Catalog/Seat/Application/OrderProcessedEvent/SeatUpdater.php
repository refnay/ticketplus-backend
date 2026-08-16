<?php

namespace App\Catalog\Seat\Application\OrderProcessedEvent;

use App\Catalog\Seat\Domain\SeatId;
use App\Catalog\Seat\Domain\SeatRepository;
use App\Catalog\Seat\Domain\SeatStatus;
use App\Catalog\Seat\Domain\Services\SeatFinder;
use App\Catalog\Shared\Domain\OrderStatusList;
use App\Catalog\Zone\Domain\ZoneId;

class SeatUpdater
{
    public function __construct(private SeatRepository $repository, private SeatFinder $finder) {}

    public function __invoke(ZoneId $zoneId, ?array $seatIds, int $status): void
    {
        if (is_null($seatIds)) {
            return;
        }

        $newStatus = match ($status) {
            OrderStatusList::PENDING->value => SeatStatus::reserved(),
            OrderStatusList::EXPIRED->value => SeatStatus::available(),
            default => null,
        };

        if (is_null($newStatus)) {
            return;
        }

        foreach ($seatIds as $seatId) {
            $seat = $this->finder->__invoke(SeatId::fromString($seatId), $zoneId);
            $seat->changeStatus($newStatus);

            $this->repository->update($seat);
        }
    }
}
