<?php

namespace App\Catalog\Seat\Application\OnPaymentApproved;

use App\Sale\Payment\Domain\Events\PaymentWithEventApprovedDomainEvent;

class PaymentApprovedEventSubscriber
{
    public function __construct(private SeatUpdater $updater)
    {
    }

    public function __invoke(PaymentWithEventApprovedDomainEvent $event): void
    {
        $this->updater->__invoke($event->seatIds());
    }
}
