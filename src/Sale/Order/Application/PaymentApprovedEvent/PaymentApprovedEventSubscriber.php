<?php

namespace App\Sale\Order\Application\PaymentApprovedEvent;

use App\Sale\Order\Domain\OrderId;
use App\Sale\Payment\Domain\Events\PaymentWithOrderApprovedDomainEvent;
use App\Sale\Reference\User\Domain\UserId;

class PaymentApprovedEventSubscriber
{
    public function __construct(private OrderUpdater $updater)
    {
    }

    public function __invoke(PaymentWithOrderApprovedDomainEvent $event): void
    {
        $this->updater->__invoke(
            OrderId::fromString($event->orderId()),
            UserId::fromString($event->userId()),
        );
    }
}
