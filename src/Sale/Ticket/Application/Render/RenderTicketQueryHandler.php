<?php

namespace App\Sale\Ticket\Application\Render;

use App\Sale\Order\Domain\OrderId;
use App\Sale\Shared\Domain\UserId;
use App\Sale\Ticket\Domain\TicketId;

class RenderTicketQueryHandler
{
    public function __construct(private TicketRender $finder)
    {
    }

    public function __invoke(RenderTicketQuery $query): TicketRenderResponse
    {
        return $this->finder->__invoke(
            TicketId::fromString($query->id()),
            OrderId::fromString($query->order()),
            UserId::fromString($query->session()->user()),
        );
    }
}
