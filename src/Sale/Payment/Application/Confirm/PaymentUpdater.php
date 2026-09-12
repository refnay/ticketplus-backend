<?php

namespace App\Sale\Payment\Application\Confirm;

use App\Sale\Order\Domain\OrderId;
use App\Sale\Order\Domain\Services\OrderFinder;
use App\Sale\Payment\Domain\Events\PaymentUpdatedDomainEvent;
use App\Sale\Payment\Domain\PaymentId;
use App\Sale\Payment\Domain\PaymentRepository;
use App\Sale\Payment\Domain\PaymentStatus;
use App\Sale\Payment\Domain\Services\UserPaymentFinder;
use App\Sale\Reference\User\Domain\UserId;
use App\Shared\Application\Bus\EventBus;

class PaymentUpdater
{
    public function __construct(
        private UserPaymentFinder $paymentFinder,
        private OrderFinder $orderFinder,
        private PaymentRepository $repository,
        private EventBus $eventBus,
    ) {}

    public function __invoke(PaymentId $id, UserId $userId, string $token): void
    {
        $payment = $this->paymentFinder->__invoke($id, $userId);
        $orderId = $payment->orderId();
        $order = $this->orderFinder->__invoke($orderId, $userId);

        $payment->changeStatus(PaymentStatus::processing());

        $this->repository->update($payment);

        $this->eventBus->publish(new PaymentUpdatedDomainEvent($payment->id(), $userId->value(), $token));
    }
}
