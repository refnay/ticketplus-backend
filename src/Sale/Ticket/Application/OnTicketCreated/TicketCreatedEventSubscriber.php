<?php

namespace App\Sale\Ticket\Application\OnTicketCreated;

use App\Sale\Reference\User\Domain\UserId;
use App\Sale\Ticket\Domain\Events\TicketCreatedDomainEvent;

class TicketCreatedEventSubscriber
{
    public function __construct(private TicketSender $sender) {}

    public function __invoke(TicketCreatedDomainEvent $event): void
    {
        $this->sender->__invoke(
            $event->ticketIds(),
            UserId::fromString($event->userId()),
        );
    }
}
