<?php

namespace App\Sale\Discount\Domain;

use App\Sale\Purchase\Domain\EventId;

interface DiscountRepository
{
    public function save(Discount $discount): void;

    public function update(Discount $discount): void;

    public function delete(Discount $discount): void;

    public function findById(DiscountId $id, EventId $eventId): ?Discount;

    public function searchByFilters(array $filters, string $orderBy, string $order, ?int $limit, ?int $offset): array;

    public function countByFilters(array $filters): int;
}