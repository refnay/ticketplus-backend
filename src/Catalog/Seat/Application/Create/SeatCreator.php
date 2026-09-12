<?php

namespace App\Catalog\Seat\Application\Create;

use App\Catalog\Seat\Domain\Exceptions\SeatAlreadyExists;
use App\Catalog\Seat\Domain\Exceptions\SeatNotFound;
use App\Catalog\Seat\Domain\Seat;
use App\Catalog\Seat\Domain\SeatCode;
use App\Catalog\Seat\Domain\SeatRepository;
use App\Catalog\Seat\Domain\Services\SeatByCodeFinder;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\Exceptions\ZoneNotNumberedSeating;
use App\Catalog\Zone\Domain\Services\CompanyZoneFinder;
use App\Catalog\Zone\Domain\ZoneId;

class SeatCreator
{
    public function __construct(
        private SeatRepository $repository,
        private CompanyZoneFinder $zoneFinder,
        private SeatByCodeFinder $seatFinder,
    ) {
    }

    public function __invoke(
        SeatCode $code,
        ZoneId $zoneId,
        CompanyId $companyId
    ): string {
        $zone = $this->zoneFinder->__invoke($zoneId, $companyId);

        if ($zone->numberedSeating()->isDisable()) {
            throw new ZoneNotNumberedSeating();
        }

        try {
            $this->seatFinder->__invoke($code, $zoneId);
            throw new SeatAlreadyExists();
        } catch (SeatNotFound) {
        }

        $seat = Seat::create($code, $zoneId);

        $this->repository->save($seat);

        return $seat->id()->value();
    }
}
