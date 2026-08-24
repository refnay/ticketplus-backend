<?php

namespace App\Catalog\Zone\Application\OrderProcessedEvent;

use App\Catalog\Event\Domain\EventDayId;
use App\Sale\Order\Domain\Events\OrderProcessedDomainEvent;

class OrderProcessedEventSubscriber
{
    public function __construct(private ZoneUpdater $updater)
    {
    }

    public function __invoke(OrderProcessedDomainEvent $event): void
    {
        $this->updater->__invoke(
            EventDayId::fromString($event->dayId()),
            $event->items(),
            $event->status(),
        );
    }
}
