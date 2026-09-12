<?php

namespace App\Catalog\Seat\Application\SearchAvailable;

use App\Catalog\Seat\Domain\Seat;
use App\Catalog\Seat\Domain\SeatRepository;
use App\Catalog\Zone\Domain\Services\PublishedZoneFinder;
use App\Catalog\Zone\Domain\ZoneId;

class SearchAvailableSeatsQueryHandler
{
    public function __construct(
        private PublishedZoneFinder $zoneFinder,
        private SeatRepository $repository,
    ) {
    }

    public function __invoke(SearchAvailableSeatsQuery $query): AvailableSeatsResponse
    {
        $this->zoneFinder->__invoke(ZoneId::fromString($query->zone()));

        $seats = $this->repository->searchByFilters(
            $query->filters(),
            $query->orderBy(),
            $query->order(),
            $query->limit(),
            $query->offset(),
        );
        $total = $this->repository->countByFilters($query->filters());

        return new AvailableSeatsResponse(
            $total,
            ...array_map(static fn(Seat $seat): AvailableSeatResponse => AvailableSeatResponse::create($seat), $seats),
        );
    }
}
