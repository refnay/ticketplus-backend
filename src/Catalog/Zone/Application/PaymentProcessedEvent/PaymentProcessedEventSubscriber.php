<?php

namespace App\Catalog\Zone\Application\PaymentProcessedEvent;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Zone\Domain\ZoneId;
use App\Sale\Payment\Domain\Events\PaymentWithEventProcessedDomainEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class PaymentProcessedEventSubscriber
{
    public function __construct(private ZoneUpdater $updater)
    {
    }

    public function __invoke(PaymentWithEventProcessedDomainEvent $event): void
    {
        $this->updater->__invoke(
            EventDayId::fromString($event->dayId()),
            ZoneId::fromString($event->zoneId()),
            $event->quantity(),
            $event->status(),
        );
    }
}
