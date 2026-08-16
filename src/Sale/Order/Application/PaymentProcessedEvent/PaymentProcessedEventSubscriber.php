<?php

namespace App\Sale\Order\Application\PaymentProcessedEvent;

use App\Sale\Order\Domain\OrderId;
use App\Sale\Payment\Domain\Events\PaymentWithOrderProcessedDomainEvent;
use App\Sale\Payment\Domain\PaymentStatus;
use App\Sale\Shared\Domain\UserId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class PaymentProcessedEventSubscriber
{
    public function __construct(private OrderUpdater $updater)
    {
    }

    public function __invoke(PaymentWithOrderProcessedDomainEvent $event): void
    {
        $this->updater->__invoke(
            OrderId::fromString($event->orderId()),
            UserId::fromString($event->userId()),
            PaymentStatus::fromInt($event->status()),
        );
    }
}
