<?php

namespace App\Sale\Order\Domain;

use App\Sale\Shared\Domain\UserId;

interface OrderRepository
{
    public function save(Order $category): void;

    public function update(Order $category): void;

    public function delete(Order $category): void;

    public function findById(OrderId $id, UserId $userId): ?Order;

    public function searchByFilters(array $filters, string $orderBy, string $order, ?int $limit, ?int $offset): array;

    public function countByFilters(array $filters): int;
}