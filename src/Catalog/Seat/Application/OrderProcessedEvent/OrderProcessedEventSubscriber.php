<?php

namespace App\Catalog\Seat\Application\OrderProcessedEvent;

use App\Sale\Order\Domain\Events\OrderProcessedDomainEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'event_bus')]
class OrderProcessedEventSubscriber
{
    public function __construct(private SeatUpdater $updater)
    {
    }

    public function __invoke(OrderProcessedDomainEvent $event): void
    {
        $this->updater->__invoke($event->items(), $event->status());
    }
}
