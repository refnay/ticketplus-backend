<?php

namespace App\Catalog\Seat\Application\PaymentProcessedEvent;

use App\Catalog\Zone\Domain\ZoneId;
use App\Sale\Payment\Domain\Events\PaymentProcessedDomainEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class PaymentApprovedEvent
{
    public function __construct(private SeatUpdater $updater)
    {
    }

    public function __invoke(PaymentProcessedDomainEvent $event): void
    {
        $this->updater->__invoke(ZoneId::fromString($event->zoneId()), $event->seatIds(), $event->status());
    }
}
