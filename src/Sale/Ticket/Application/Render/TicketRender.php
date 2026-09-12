<?php

namespace App\Sale\Ticket\Application\Render;

use App\Sale\Order\Domain\OrderId;
use App\Sale\Order\Domain\Services\OrderFinder;
use App\Sale\Reference\User\Domain\UserId;
use App\Sale\Ticket\Domain\Services\TicketFinder;
use App\Sale\Ticket\Domain\TicketId;
use App\Shared\Application\Port\Pdf\PdfGenerator;
use App\Shared\Domain\Utils\StringHelper as StringValue;

final readonly class TicketRender
{
    public function __construct(
        private TicketFinder $ticketFinder,
        private OrderFinder $orderFinder,
        private PdfGenerator $pdfGenerator,
    ) {
    }

    public function __invoke(TicketId $id, UserId $userId): TicketRenderResponse
    {
        $ticket = $this->ticketFinder->__invoke($id, $userId);
        $order = $this->orderFinder->__invoke($ticket->orderId(), $userId);

        $filename = StringValue::normalize($ticket->filename());

        $pdf = $this->pdfGenerator
            ->prepare([
                ...$ticket->toArray(),
                'currency' => $order->currency()->value(),
            ])
            ->setTemplate('ticket/ticket.html.twig')
            ->setFilename($filename);

        return new TicketRenderResponse($pdf->generate(), $pdf->filename());
    }
}
