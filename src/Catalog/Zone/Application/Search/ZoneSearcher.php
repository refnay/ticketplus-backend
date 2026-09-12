<?php

namespace App\Catalog\Zone\Application\Search;

use App\Catalog\Zone\Domain\Zone;
use App\Catalog\Zone\Domain\ZoneRepository;

class ZoneSearcher
{
    public function __construct(private ZoneRepository $repository)
    {
    }

    public function __invoke(
        array $filters,
        string $orderBy,
        string $order,
        ?int $limit,
        ?int $offset,
    ): ZonesResponse {
        $zones = $this->repository->searchByFilters($filters, $orderBy, $order, $limit, $offset);
        $total = $this->repository->countByFilters($filters);

        return new ZonesResponse($total, ...array_map($this->makeResponse(), $zones));
    }

    private function makeResponse(): callable
    {
        return fn(Zone $zone) => ZoneResponse::create($zone);
    }
}
