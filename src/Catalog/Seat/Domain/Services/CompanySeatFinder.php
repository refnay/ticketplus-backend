<?php

namespace App\Catalog\Seat\Domain\Services;

use App\Catalog\Seat\Domain\Exceptions\SeatNotFound;
use App\Catalog\Seat\Domain\Seat;
use App\Catalog\Seat\Domain\SeatId;
use App\Catalog\Seat\Domain\SeatRepository;
use App\Catalog\Shared\Domain\CompanyId;

class CompanySeatFinder
{
    public function __construct(private SeatRepository $repository)
    {
    }

    public function __invoke(SeatId $id, CompanyId $companyId): Seat
    {
        $seat = $this->repository->findById($id, $companyId);

        if (is_null($seat)) {
            throw new SeatNotFound();
        }

        return $seat;
    }
}
