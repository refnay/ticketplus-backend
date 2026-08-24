<?php

namespace App\Sale\Payment\Application\PaymentUpdatedEvent;

use App\Sale\Order\Domain\OrderId;
use App\Sale\Payment\Domain\Events\PaymentUpdatedDomainEvent;
use App\Sale\Payment\Domain\PaymentId;
use App\Sale\Reference\User\Domain\UserId;

class PaymentUpdatedEventSubscriber
{
    public function __construct(private PaymentProcessor $processor)
    {
    }

    public function __invoke(PaymentUpdatedDomainEvent $event): void
    {
        $this->processor->__invoke(
            PaymentId::fromString($event->paymentId()),
            OrderId::fromString($event->orderId()),
            UserId::fromString($event->userId()),
            $event->token(),
        );
    }
}
