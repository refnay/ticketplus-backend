<?php

namespace App\Sale\Payment\Domain;

use App\Sale\Payment\Domain\Payment;
use App\Sale\Payment\Domain\PaymentId;
use App\Sale\Order\Domain\OrderId;

interface PaymentRepository
{
    public function save(Payment $discount): void;

    public function update(Payment $discount): void;

    public function delete(Payment $discount): void;

    public function findById(PaymentId $id, OrderId $orderId): ?Payment;

    public function searchByFilters(array $filters, string $orderBy, string $order, ?int $limit, ?int $offset): array;

    public function countByFilters(array $filters): int;
}