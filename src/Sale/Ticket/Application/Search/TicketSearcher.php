<?php

namespace App\Sale\Ticket\Application\Search;

use App\Sale\Ticket\Domain\Ticket;
use App\Sale\Ticket\Domain\TicketRepository;

final readonly class TicketSearcher
{
    public function __construct(private TicketRepository $repository) {}

    public function __invoke(
        array $filters,
        string $orderBy,
        string $order,
        ?int $limit,
        ?int $offset,
    ): TicketsResponse {
        $tickets = $this->repository->searchByFilters($filters, $orderBy, $order, $limit, $offset);
        $total = $this->repository->countByFilters($filters);

        return new TicketsResponse($total, ...array_map($this->makeResponse(), $tickets));
    }

    private function makeResponse(): callable
    {
        return fn(Ticket $ticket) => TicketResponse::create($ticket);
    }
}