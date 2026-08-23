<?php

namespace App\Sale\Ticket\Application\TicketCreatedEvent;

use App\Sale\Order\Domain\OrderId;
use App\Sale\Shared\Domain\UserId;

class TicketSender
{
    public function __construct()
    {
    }

    public function __invoke(array $ticketIds, OrderId $orderId, UserId $userId): void
    {
    }
}
