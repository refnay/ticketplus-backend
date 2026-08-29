<?php

namespace App\Catalog\Seat\Application\BulkCreate;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\Exceptions\EventDayNotFound;
use App\Catalog\Event\Domain\Services\EventFinder;
use App\Catalog\Seat\Domain\Exceptions\SeatAlreadyExists;
use App\Catalog\Seat\Domain\Exceptions\SeatNotCreated;
use App\Catalog\Seat\Domain\Exceptions\SeatNotFound;
use App\Catalog\Seat\Domain\Seat;
use App\Catalog\Seat\Domain\SeatCode;
use App\Catalog\Seat\Domain\SeatRepository;
use App\Catalog\Seat\Domain\Services\SeatByCodeFinder;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\Exceptions\ZoneNotNumberedSeating;
use App\Catalog\Zone\Domain\Services\ZoneFinder;
use App\Catalog\Zone\Domain\ZoneId;
use App\Shared\Application\Transaction\TransactionService;
use Throwable;

class SeatBulkCreator
{
    public function __construct(
        private SeatRepository $repository,
        private EventFinder $eventFinder,
        private ZoneFinder $zoneFinder,
        private SeatByCodeFinder $seatFinder,
        private TransactionService $transaction,
    ) {}

    public function __invoke(
        EventId $eventId,
        EventDayId $dayId,
        ZoneId $zoneId,
        CompanyId $companyId,
        array $seats,
    ): void {
        $event = $this->eventFinder->__invoke($eventId, $companyId);
        $day = $event->findDayById($dayId);

        if (is_null($day)) {
            throw new EventDayNotFound();
        }

        $zone = $this->zoneFinder->__invoke($zoneId, $dayId);

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
