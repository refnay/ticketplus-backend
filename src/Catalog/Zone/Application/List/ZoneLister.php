<?php

namespace App\Catalog\Zone\Application\List;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Event\Domain\EventId;
use App\Catalog\Event\Domain\Exceptions\EventDayNotFound;
use App\Catalog\Event\Domain\Services\EventFinder;
use App\Catalog\Shared\Domain\CompanyId;
use App\Catalog\Zone\Domain\Zone;
use App\Catalog\Zone\Domain\ZoneRepository;

class ZoneLister
{
    public function __construct(private ZoneRepository $repository, private EventFinder $eventFinder)
    {
    }

    public function __invoke(
        EventId $eventId,
        EventDayId $dayId,
        CompanyId $companyId,
        array $filters,
        string $orderBy,
        string $order,
        ?int $limit,
        ?int $offset,
    ): ZonesResponse {
        $event = $this->eventFinder->__invoke($eventId, $companyId);
        $day = $event->findDayById($dayId);

        if (is_null($day)) {
            throw new EventDayNotFound();
        }

        $zones = $this->repository->searchByFilters($filters, $orderBy, $order, $limit, $offset);
        $total = $this->repository->countByFilters($filters);

        return new ZonesResponse($total, ...array_map($this->makeResponse(), $zones));
    }

    private function makeResponse(): callable
    {
        return fn(Zone $zone) => ZoneResponse::create($zone);
    }
}