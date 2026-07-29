<?php

namespace App\Catalog\Seat\Application\List;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\Exceptions\EventDayNotFound;
use App\Catalog\Event\Domain\Services\EventFinder;
use App\Catalog\Seat\Domain\Seat;
use App\Catalog\Seat\Domain\SeatRepository;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\Services\ZoneFinder;
use App\Catalog\Zone\Domain\ZoneId;

class SeatLister
{
    public function __construct(private SeatRepository $repository, private EventFinder $eventFinder, private ZoneFinder $zoneFinder)
    {
    }

    public function __invoke(
        EventId $eventId,
        EventDayId $dayId,
        ZoneId $zoneId,
        CompanyId $companyId,
        array $filters,
        string $orderBy,
        string $order,
        ?int $limit,
        ?int $offset,
    ): SeatsResponse {
        $event = $this->eventFinder->__invoke($eventId, $companyId);
        $day = $event->findDayById($dayId);

        if (is_null($day)) {
            throw new EventDayNotFound();
        }

        $this->zoneFinder->__invoke($zoneId, $day->id());

        $seats = $this->repository->searchByFilters($filters, $orderBy, $order, $limit, $offset);
        $total = $this->repository->countByFilters($filters);

        return new SeatsResponse($total, ...array_map($this->makeResponse(), $seats));
    }

    private function makeResponse(): callable
    {
        return fn(Seat $seat) => SeatResponse::create($seat);
    }
}