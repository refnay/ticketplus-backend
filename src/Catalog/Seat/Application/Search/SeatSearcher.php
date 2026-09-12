<?php

namespace App\Catalog\Seat\Application\Search;

use App\Catalog\Seat\Domain\Seat;
use App\Catalog\Seat\Domain\SeatRepository;

class SeatSearcher
{
    public function __construct(private SeatRepository $repository)
    {
    }

    public function __invoke(
        array $filters,
        string $orderBy,
        string $order,
        ?int $limit,
        ?int $offset,
    ): SeatsResponse {
        $seats = $this->repository->searchByFilters($filters, $orderBy, $order, $limit, $offset);
        $total = $this->repository->countByFilters($filters);

        return new SeatsResponse($total, ...array_map($this->makeResponse(), $seats));
    }

    private function makeResponse(): callable
    {
        return fn(Seat $seat) => SeatResponse::create($seat);
    }
}
