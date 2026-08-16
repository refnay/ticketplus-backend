<?php

namespace App\Catalog\Zone\Application\OrderProcessedEvent;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Zone\Domain\ZoneId;
use App\Sale\Order\Domain\Events\OrderProcessedDomainEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class OrderProcessedEventSubscriber
{
    public function __construct(private ZoneUpdater $updater)
    {
    }

    public function __invoke(OrderProcessedDomainEvent $event): void
    {
        $this->updater->__invoke(
            EventDayId::fromString($event->dayId()),
            ZoneId::fromString($event->zoneId()),
            $event->quantity(),
            $event->status(),
        );
    }
}
