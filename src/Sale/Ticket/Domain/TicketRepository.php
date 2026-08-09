<?php

namespace App\Sale\Ticket\Domain;

use App\Sale\Order\Domain\OrderId;

interface TicketRepository
{
    public function save(Ticket $ticket): void;

    public function update(Ticket $ticket): void;

    public function delete(Ticket $ticket): void;

    public function findById(TicketId $id, OrderId $orderId): ?Ticket;

    public function searchByFilters(array $filters, string $orderBy, string $order, ?int $limit, ?int $offset): array;

    public function countByFilters(array $filters): int;
}