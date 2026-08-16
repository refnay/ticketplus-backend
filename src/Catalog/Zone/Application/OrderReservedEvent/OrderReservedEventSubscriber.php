<?php

namespace App\Catalog\Zone\Application\OrderReservedEvent;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Zone\Domain\ZoneId;
use App\Sale\Order\Domain\Events\OrderReservedDomainEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class OrderReservedEventSubscriber
{
    public function __construct(private ZoneUpdater $updater)
    {
    }

    public function __invoke(OrderReservedDomainEvent $event): void
    {
        $this->updater->__invoke(
            EventDayId::fromString($event->dayId()),
            ZoneId::fromString($event->zoneId()),
            $event->quantity()
        );
    }
}
