<?php

namespace App\Catalog\Seat\Application\Delete;

use App\Catalog\Seat\Domain\SeatId;
use App\Catalog\Seat\Domain\SeatRepository;
use App\Catalog\Seat\Domain\Services\CompanySeatFinder;
use App\Catalog\Shared\Domain\CompanyId;

class SeatDeleter
{
    public function __construct(
        private SeatRepository $repository,
        private CompanySeatFinder $seatFinder,
    ) {
    }

    public function __invoke(SeatId $id, CompanyId $companyId): void
    {
        $seat = $this->seatFinder->__invoke($id, $companyId);

        $this->repository->delete($seat);
    }
}
