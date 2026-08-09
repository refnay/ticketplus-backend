<?php

namespace App\Sale\Ticket\Domain\Services;

use App\Sale\Purchase\Domain\PurchaseId;
use App\Sale\Ticket\Domain\Exceptions\TicketNotFound;
use App\Sale\Ticket\Domain\Ticket;
use App\Sale\Ticket\Domain\TicketId;
use App\Sale\Ticket\Domain\TicketRepository;

class TicketFinder
{
    public function __construct(private TicketRepository $repository)
    {
    }

    public function __invoke(TicketId $id, PurchaseId $purchaseId): Ticket
    {
        $ticket = $this->repository->findById($id, $purchaseId);

        if (is_null($ticket)) {
            throw new TicketNotFound();
        }

        return $ticket;
    }
}