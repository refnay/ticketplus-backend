<?php

namespace App\Catalog\Zone\Application\PaymentApprovedEvent;

use App\Catalog\Event\Domain\EventDayId;
use App\Catalog\Zone\Domain\ZoneId;
use App\Sale\Payment\Domain\Events\PaymentApprovedDomainEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class PaymentApprovedEventSubscriber
{
    public function __construct(private ZoneUpdater $updater)
    {
    }

    public function __invoke(PaymentApprovedDomainEvent $event): void
    {
        $this->updater->__invoke(
            EventDayId::fromString($event->dayId()),
            ZoneId::fromString($event->zoneId()),
            $event->quantity()
        );
    }
}
