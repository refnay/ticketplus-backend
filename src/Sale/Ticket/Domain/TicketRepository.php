<?php

namespace App\Sale\Ticket\Domain;

use App\Sale\Purchase\Domain\PurchaseId;

interface TicketRepository
{
    public function save(Ticket $ticket): void;

    public function update(Ticket $ticket): void;

    public function delete(Ticket $ticket): void;

    public function findById(TicketId $id, PurchaseId $purchaseId): ?Ticket;

    public function searchByFilters(array $filters, string $orderBy, string $order, ?int $limit, ?int $offset): array;

    public function countByFilters(array $filters): int;
}