<?php

namespace App\Catalog\Seat\Application\Find;

use App\Catalog\Seat\Domain\SeatId;
use App\Catalog\Seat\Domain\Services\SeatByCompanyFinder;
use App\Catalog\Shared\Domain\CompanyId;

class SeatFinder
{
    public function __construct(
        private SeatByCompanyFinder $seatFinder
    ) {}

    public function __invoke(SeatId $id, CompanyId $companyId): SeatResponse
    {
        $seat = $this->seatFinder->__invoke($id, $companyId);

        return SeatResponse::create($seat);
    }
}
