<?php

namespace App\Sale\Payment\Application\Update;

use App\Sale\Order\Domain\OrderId;
use App\Sale\Order\Domain\Services\OrderFinder;
use App\Sale\Payment\Domain\Events\PaymentUpdatedDomainEvent;
use App\Sale\Payment\Domain\PaymentId;
use App\Sale\Payment\Domain\PaymentRepository;
use App\Sale\Payment\Domain\PaymentStatus;
use App\Sale\Payment\Domain\Services\PaymentFinder;
use App\Sale\Shared\Domain\UserId;
use App\Shared\Application\Messenger\EventBus;
use App\Shared\Domain\Utils\Primitive\ArrayBuilder;

class PaymentUpdater
{
    private ArrayBuilder $events;

    public function __construct(
        private PaymentFinder $paymentFinder,
        private OrderFinder $orderFinder,
        private PaymentRepository $repository,
        private EventBus $eventBus,
    ) {
        $this->events = ArrayBuilder::generate();
    }

    public function __invoke(PaymentId $id, OrderId $orderId, UserId $userId, string $token): void
    {
        $order = $this->orderFinder->__invoke($orderId, $userId);
        $payment = $this->paymentFinder->__invoke($id, $order->id());

        $payment->changeStatus(PaymentStatus::processing());

        $this->repository->update($payment);

        $this->events->add(new PaymentUpdatedDomainEvent($payment->id(), $orderId->value(), $userId->value(), $token));
        $this->eventBus->dispatch(...$this->events->items());
    }
}
