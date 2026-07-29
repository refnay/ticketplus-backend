<?php

namespace App\Catalog\Zone\Domain;

use App\Catalog\Event\Domain\EventDayId;

interface ZoneRepository
{
    public function save(Zone $zone): void;

    public function update(Zone $zone): void;

    public function delete(Zone $zone): void;

    public function findById(ZoneId $id, EventDayId $dayId): ?Zone;

    public function searchByFilters(array $filters, string $orderBy, string $order, ?int $limit, ?int $offset): array;

    public function countByFilters(array $filters): int;
}