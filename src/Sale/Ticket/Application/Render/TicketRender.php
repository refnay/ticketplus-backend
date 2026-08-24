<?php

namespace App\Sale\Ticket\Application\Render;

use App\Sale\Order\Domain\OrderId;
use App\Sale\Order\Domain\Services\OrderFinder;
use App\Sale\Reference\User\Domain\UserId;
use App\Sale\Ticket\Domain\Services\TicketFinder;
use App\Sale\Ticket\Domain\TicketId;
use App\Shared\Application\Port\Pdf\PdfGenerator;
use App\Shared\Domain\Utils\StringHelper;

final readonly class TicketRender
{
    public function __construct(
        private TicketFinder $ticketFinder,
        private OrderFinder $orderFinder,
        private PdfGenerator $pdfGenerator,
    ) {
    }

    public function __invoke(TicketId $id, OrderId $orderId, UserId $userId): TicketRenderResponse
    {
        $order = $this->orderFinder->__invoke($orderId, $userId);
        $ticket = $this->ticketFinder->__invoke($id, $orderId);

        $filename = StringHelper::normalize($ticket->filename());

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
