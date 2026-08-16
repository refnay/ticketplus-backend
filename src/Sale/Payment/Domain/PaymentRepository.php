<?php

namespace App\Sale\Payment\Domain;

use App\Sale\Payment\Domain\Payment;
use App\Sale\Payment\Domain\PaymentId;
use App\Sale\Order\Domain\OrderId;

interface PaymentRepository
{
    public function save(Payment $payment): void;

    public function update(Payment $payment): void;

    public function delete(Payment $payment): void;

    public function findById(PaymentId $id, OrderId $orderId): ?Payment;

    public function findByExternalReference(PaymentExternalReference $id, OrderId $orderId): ?Payment;

    public function getProcessing(OrderId $orderId): ?Payment;

    public function searchByFilters(array $filters, string $orderBy, string $order, ?int $limit, ?int $offset): array;

    public function countByFilters(array $filters): int;
}