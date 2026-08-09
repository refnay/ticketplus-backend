<?php

namespace App\Sale\Ticket\Domain\Services;

use App\Sale\Order\Domain\OrderId;
use App\Sale\Ticket\Domain\Exceptions\TicketNotFound;
use App\Sale\Ticket\Domain\Ticket;
use App\Sale\Ticket\Domain\TicketId;
use App\Sale\Ticket\Domain\TicketRepository;

class TicketFinder
{
    public function __construct(private TicketRepository $repository)
    {
    }

    public function __invoke(TicketId $id, OrderId $orderId): Ticket
    {
        $ticket = $this->repository->findById($id, $orderId);

        if (is_null($ticket)) {
            throw new TicketNotFound();
        }

        return $ticket;
    }
}