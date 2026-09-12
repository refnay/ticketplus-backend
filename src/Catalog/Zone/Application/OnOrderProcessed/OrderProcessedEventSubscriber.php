<?php

namespace App\Catalog\Zone\Application\OnOrderProcessed;

use App\Sale\Order\Domain\Events\OrderProcessedDomainEvent;

class OrderProcessedEventSubscriber
{
    public function __construct(private ZoneUpdater $updater)
    {
    }

    public function __invoke(OrderProcessedDomainEvent $event): void
    {
        $this->updater->__invoke(
            $event->items(),
            $event->status(),
        );
    }
}
