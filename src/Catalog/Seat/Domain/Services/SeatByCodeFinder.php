<?php

namespace App\Catalog\Seat\Domain\Services;

use App\Catalog\Seat\Domain\Exceptions\SeatNotFound;
use App\Catalog\Seat\Domain\Seat;
use App\Catalog\Seat\Domain\SeatCode;
use App\Catalog\Seat\Domain\SeatRepository;
use App\Catalog\Shared\Domain\CompanyId;

class SeatByCodeFinder
{
    public function __construct(private SeatRepository $repository)
    {
    }

    public function __invoke(SeatCode $code, CompanyId $companyId): Seat
    {
        $seat = $this->repository->findByCode($code, $companyId);

        if (is_null($seat)) {
            throw new SeatNotFound();
        }

        return $seat;
    }
}