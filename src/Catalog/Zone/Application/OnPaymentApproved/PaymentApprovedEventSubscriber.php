<?php

namespace App\Catalog\Zone\Application\OnPaymentApproved;

use App\Catalog\Zone\Domain\ZoneId;
use App\Sale\Payment\Domain\Events\PaymentWithEventApprovedDomainEvent;

class PaymentApprovedEventSubscriber
{
    public function __construct(private ZoneUpdater $updater)
    {
    }

    public function __invoke(PaymentWithEventApprovedDomainEvent $event): void
    {
        $this->updater->__invoke(
            ZoneId::fromString($event->zoneId()),
            $event->quantity(),
        );
    }
}
