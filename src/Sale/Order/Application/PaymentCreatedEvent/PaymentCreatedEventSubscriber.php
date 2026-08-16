<?php

namespace App\Sale\Order\Application\PaymentCreatedEvent;

use App\Sale\Order\Domain\OrderId;
use App\Sale\Order\Domain\OrderPaymentMethod;
use App\Sale\Payment\Domain\Events\PaymentCreatedDomainEvent;
use App\Sale\Shared\Domain\UserId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class PaymentCreatedEventSubscriber
{
    public function __construct(private OrderUpdater $updater)
    {
    }

    public function __invoke(PaymentCreatedDomainEvent $event): void
    {
        $this->updater->__invoke(
            OrderId::fromString($event->orderId()),
            OrderPaymentMethod::fromInt($event->paymentMethod()),
            UserId::fromString($event->userId()),
        );
    }
}
