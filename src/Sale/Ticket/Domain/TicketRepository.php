<?php

namespace App\Sale\Ticket\Domain;

use App\Sale\Order\Domain\OrderPaidAt;
use App\Sale\Reference\User\Domain\UserId;
use App\Sale\Shared\Domain\CompanyId;

interface TicketRepository
{
    public function save(Ticket $ticket): void;

    public function update(Ticket $ticket): void;

    public function delete(Ticket $ticket): void;

    public function findById(TicketId $id, UserId $userId): ?Ticket;

    public function searchByFilters(array $filters, string $orderBy, string $order, ?int $limit, ?int $offset): array;

    public function countByFilters(array $filters): int;

    public function countSoldTickets(
        CompanyId $companyId,
        OrderPaidAt $from,
        OrderPaidAt $to,
    ): int;
}
