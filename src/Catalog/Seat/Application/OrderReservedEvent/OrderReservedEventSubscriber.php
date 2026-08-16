<?php

namespace App\Catalog\Seat\Application\OrderReservedEvent;

use App\Catalog\Zone\Domain\ZoneId;
use App\Sale\Order\Domain\Events\OrderReservedDomainEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class OrderReservedEventSubscriber
{
    public function __construct(private SeatUpdater $updater)
    {
    }

    public function __invoke(OrderReservedDomainEvent $event): void
    {
        $this->updater->__invoke(ZoneId::fromString($event->zoneId()), $event->seatIds());
    }
}
