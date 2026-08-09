<?php

namespace App\Sale\Purchase\Domain;

use App\Sale\Shared\Domain\UserId;

interface PurchaseRepository
{
    public function save(Purchase $category): void;

    public function update(Purchase $category): void;

    public function delete(Purchase $category): void;

    public function findById(PurchaseId $id, UserId $userId): ?Purchase;

    public function searchByFilters(array $filters, string $orderBy, string $order, ?int $limit, ?int $offset): array;

    public function countByFilters(array $filters): int;
}