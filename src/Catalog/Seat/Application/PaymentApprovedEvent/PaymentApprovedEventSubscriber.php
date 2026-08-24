<?php

namespace App\Catalog\Seat\Application\PaymentApprovedEvent;

use App\Catalog\Zone\Domain\ZoneId;
use App\Sale\Payment\Domain\Events\PaymentWithEventApprovedDomainEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'event_bus')]
class PaymentApprovedEventSubscriber
{
    public function __construct(private SeatUpdater $updater)
    {
    }

    public function __invoke(PaymentWithEventApprovedDomainEvent $event): void
    {
        $this->updater->__invoke(ZoneId::fromString($event->zoneId()), $event->seatIds());
    }
}
