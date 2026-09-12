<?php

namespace App\Catalog\Seat\Application\BulkCreate;

use App\Catalog\Seat\Domain\Exceptions\SeatAlreadyExists;
use App\Catalog\Seat\Domain\Exceptions\SeatNotCreated;
use App\Catalog\Seat\Domain\Exceptions\SeatNotFound;
use App\Catalog\Seat\Domain\Seat;
use App\Catalog\Seat\Domain\SeatCode;
use App\Catalog\Seat\Domain\SeatRepository;
use App\Catalog\Seat\Domain\Services\SeatByCodeFinder;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\Exceptions\ZoneNotNumberedSeating;
use App\Catalog\Zone\Domain\Services\CompanyZoneFinder;
use App\Catalog\Zone\Domain\ZoneId;
use App\Shared\Application\Transaction\TransactionService;
use Throwable;

class SeatBulkCreator
{
    public function __construct(
        private SeatRepository $repository,
        private CompanyZoneFinder $zoneFinder,
        private SeatByCodeFinder $seatFinder,
        private TransactionService $transaction,
    ) {}

    public function __invoke(
        ZoneId $zoneId,
        CompanyId $companyId,
        array $seats,
    ): void {
        $zone = $this->zoneFinder->__invoke($zoneId, $companyId);

        if ($zone->numberedSeating()->isDisable()) {
            throw new ZoneNotNumberedSeating();
        }

        $this->transaction->begin();
        try {
            foreach ($seats as $seat) {
                $code = SeatCode::fromString($seat);

                try {
                    $this->seatFinder->__invoke($code, $zoneId);
                    throw new SeatAlreadyExists();
                } catch (SeatNotFound) {
                }

                $seat = Seat::create($code, $zoneId);

                $this->repository->save($seat);
            }
            $this->transaction->commit();
        } catch (Throwable) {
            $this->transaction->rollback();
            throw new SeatNotCreated();
        }
    }
}
