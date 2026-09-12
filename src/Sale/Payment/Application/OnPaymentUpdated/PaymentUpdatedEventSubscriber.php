<?php

namespace App\Sale\Payment\Application\OnPaymentUpdated;

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
            UserId::fromString($event->userId()),
            $event->token(),
        );
    }
}
