<?php

namespace App\Catalog\Zone\Domain;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Shared\Domain\CompanyId;

interface ZoneRepository
{
    public function save(Zone $zone): void;

    public function update(Zone $zone): void;

    public function delete(Zone $zone): void;

    public function find(ZoneId $id): ?Zone;

    public function findById(ZoneId $id, CompanyId $companyId): ?Zone;

    public function findPublishedById(ZoneId $id): ?Zone;

    public function searchByFilters(array $filters, string $orderBy, string $order, ?int $limit, ?int $offset): array;

    public function countByFilters(array $filters): int;

    public function occupancySummary(CompanyId $companyId): array;
}
