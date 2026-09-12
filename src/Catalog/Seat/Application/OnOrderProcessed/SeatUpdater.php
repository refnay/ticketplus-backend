<?php

namespace App\Catalog\Seat\Application\OnOrderProcessed;

use App\Catalog\Seat\Domain\SeatId;
use App\Catalog\Seat\Domain\SeatRepository;
use App\Catalog\Seat\Domain\SeatStatus;
use App\Catalog\Seat\Domain\Services\SeatFinder;
use App\Catalog\Shared\Domain\OrderStatusList;

class SeatUpdater
{
    public function __construct(private SeatRepository $repository, private SeatFinder $finder)
    {
    }

    public function __invoke(array $items, int $status): void
    {
        $newStatus = match ($status) {
            OrderStatusList::PENDING->value => SeatStatus::reserved(),
            OrderStatusList::EXPIRED->value => SeatStatus::available(),
            default => null,
        };

        if (is_null($newStatus)) {
            return;
        }

        foreach ($items as $item) {
            $seatIds = $item['seats'];

            if (is_array($seatIds) && count($seatIds) > 0) {
                foreach ($seatIds as $seatId) {
                    $seat = $this->finder->__invoke(SeatId::fromString($seatId));
                    $seat->changeStatus($newStatus);

                    $this->repository->update($seat);
                }
            }
        }
    }
}
