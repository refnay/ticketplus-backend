<?php

namespace App\Sale\Ticket\Application\Render;

use App\Shared\Application\Security\AuthorizationContext;
use App\Sale\Order\Domain\OrderId;
use App\Sale\Reference\User\Domain\UserId;
use App\Sale\Ticket\Domain\TicketId;

class RenderTicketQueryHandler
{
    public function __construct(private AuthorizationContext $authorization, private TicketRender $finder)
    {
    }

    public function __invoke(RenderTicketQuery $query): TicketRenderResponse
    {
        return $this->finder->__invoke(
            TicketId::fromString($query->id()),
            OrderId::fromString($query->order()),
            UserId::fromString($this->authorization->userId()),
        );
    }
}
