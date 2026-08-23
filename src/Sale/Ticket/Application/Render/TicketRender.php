<?php

namespace App\Sale\Ticket\Application\Render;

use App\Sale\Order\Domain\OrderId;
use App\Sale\Order\Domain\Services\OrderFinder;
use App\Sale\Shared\Domain\UserId;
use App\Sale\Ticket\Domain\Services\TicketFinder;
use App\Sale\Ticket\Domain\TicketId;
use App\Shared\Application\Pdf\PdfGenerator;

final readonly class TicketRender
{
    public function __construct(
        private TicketFinder $ticketFinder,
        private OrderFinder $orderFinder,
        private PdfGenerator $pdfGenerator,
    ) {
    }

    public function __invoke(TicketId $id, OrderId $orderId, UserId $userId): string
    {
        $this->orderFinder->__invoke($orderId, $userId);
        
        $ticket = $this->ticketFinder->__invoke($id, $orderId);

        return $this->pdfGenerator
            ->prepare($ticket->toArray())
            ->setTemplate('ticket/ticket.html.twig')
            ->generate();
    }
}