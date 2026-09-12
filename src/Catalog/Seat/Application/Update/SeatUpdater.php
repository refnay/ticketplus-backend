<?php

namespace App\Catalog\Seat\Application\Update;

use App\Catalog\Seat\Domain\Exceptions\SeatAlreadyExists;
use App\Catalog\Seat\Domain\Exceptions\SeatNotFound;
use App\Catalog\Seat\Domain\SeatCode;
use App\Catalog\Seat\Domain\SeatId;
use App\Catalog\Seat\Domain\SeatRepository;
use App\Catalog\Seat\Domain\SeatStatus;
use App\Catalog\Seat\Domain\Services\SeatByCodeFinder;
use App\Catalog\Seat\Domain\Services\CompanySeatFinder;
use App\Catalog\Shared\Domain\CompanyId;

class SeatUpdater
{
    public function __construct(
        private SeatRepository $repository,
        private CompanySeatFinder $seatFinder,
        private SeatByCodeFinder $seatByCodeFinder,
    ) {
    }

    public function __invoke(
        SeatId $id,
        SeatCode $code,
        SeatStatus $status,
        CompanyId $companyId,
    ): void {
        $seat = $this->seatFinder->__invoke($id, $companyId);
        $zoneId = $seat->zoneId();

        if (!$seat->code()->equals($code)) {
            try {
                $this->seatByCodeFinder->__invoke($code, $zoneId);
                throw new SeatAlreadyExists();
            } catch (SeatNotFound) {
            }
        }

        $seat->changeCode($code);
        $seat->changeStatus($status);

        $this->repository->update($seat);
    }
}
