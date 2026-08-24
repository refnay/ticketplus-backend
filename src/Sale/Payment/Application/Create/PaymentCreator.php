<?php

namespace App\Sale\Payment\Application\Create;

use App\Sale\Order\Domain\Exceptions\OrderExpired;
use App\Sale\Order\Domain\Exceptions\OrderStatusNotAllowed;
use App\Sale\Order\Domain\OrderId;
use App\Sale\Order\Domain\Services\OrderFinder;
use App\Sale\Payment\Domain\Events\PaymentCreatedDomainEvent;
use App\Sale\Payment\Domain\Exceptions\PaymentAlreadyProcessing;
use App\Sale\Payment\Domain\Exceptions\PaymentNotFound;
use App\Sale\Payment\Domain\Payment;
use App\Sale\Payment\Domain\PaymentAmount;
use App\Sale\Payment\Domain\PaymentMethod;
use App\Sale\Payment\Domain\PaymentPayer;
use App\Sale\Payment\Domain\PaymentRepository;
use App\Sale\Payment\Domain\Services\PaymentProcessingFinder;
use App\Sale\User\Domain\UserId;
use App\Shared\Application\Bus\EventBus;

class PaymentCreator
{
    public function __construct(
        private OrderFinder $orderFinder,
        private PaymentRepository $repository,
        private EventBus $eventBus,
        private PaymentProcessingFinder $paymentFinder,
    ) {}

    public function __invoke(OrderId $orderId, PaymentMethod $method, PaymentPayer $payer, UserId $userId): string
    {
        $order = $this->orderFinder->__invoke($orderId, $userId);

        if (!$order->status()->isPending()) {
            throw new OrderStatusNotAllowed();
        }

        if ($order->expiresAt()->expired()) {
            throw new OrderExpired();
        }

        try {
            $this->paymentFinder->__invoke($orderId);
            throw new PaymentAlreadyProcessing();
        } catch (PaymentNotFound) {
        }

        $payment = Payment::create(
            PaymentAmount::fromFloat($order->total()->value()),
            $method,
            $payer,
            $orderId,
        );

        $this->repository->save($payment);

        $this->eventBus->publish(new PaymentCreatedDomainEvent($orderId->value(), $method->value(), $userId->value()));

        return $payment->id()->value();
    }
}
