<?php

namespace App\Catalog\Seat\Domain;

use App\Catalog\Zone\Domain\ZoneId;
use App\Catalog\Shared\Domain\CompanyId;

interface SeatRepository
{
    public function save(Seat $seat): void;

    public function update(Seat $seat): void;

    public function delete(Seat $seat): void;

    public function find(SeatId $id): ?Seat;

    public function findById(SeatId $id, CompanyId $companyId): ?Seat;

    public function findByCode(SeatCode $code, ZoneId $zoneId): ?Seat;

    public function searchByFilters(array $filters, string $orderBy, string $order, ?int $limit, ?int $offset): array;

    public function countByFilters(array $filters): int;
}
