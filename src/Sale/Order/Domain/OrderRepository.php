<?php

namespace App\Sale\Order\Domain;

use App\Sale\Reference\User\Domain\UserId;
use App\Sale\Shared\Domain\CompanyId;

interface OrderRepository
{
    public function save(Order $order): void;

    public function update(Order $order): void;

    public function delete(Order $order): void;

    public function find(OrderId $id): ?Order;

    public function findById(OrderId $id, UserId $userId): ?Order;

    public function findExpireds(): array;

    public function searchByFilters(array $filters, string $orderBy, string $order, ?int $limit, ?int $offset): array;

    public function countByFilters(array $filters): int;

    public function amountPaidTotal(
        CompanyId $companyId,
        OrderCurrency $currency,
        OrderPaidAt $from,
        OrderPaidAt $to,
    ): float;
}
