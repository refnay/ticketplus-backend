<?php

namespace App\Catalog\Seat\Application\OnOrderProcessed;

use App\Sale\Order\Domain\Events\OrderProcessedDomainEvent;

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
