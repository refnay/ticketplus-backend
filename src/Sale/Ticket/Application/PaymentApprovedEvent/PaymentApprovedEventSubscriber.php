<?php

namespace App\Sale\Ticket\Application\PaymentApprovedEvent;

use App\Sale\Order\Domain\OrderId;
use App\Sale\Payment\Domain\Events\PaymentWithTicketApprovedDomainEvent;
use App\Sale\Shared\Domain\UserId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class PaymentApprovedEventSubscriber
{
    public function __construct(private TicketCreator $creator)
    {
    }

    public function __invoke(PaymentWithTicketApprovedDomainEvent $event): void
    {
        $this->creator->__invoke(
            OrderId::fromString($event->orderId()),
            UserId::fromString($event->userId()),
        );
    }
}
