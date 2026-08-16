<?php

namespace App\Sale\Payment\Application\Update;

use App\Sale\Order\Domain\OrderId;
use App\Sale\Order\Domain\Services\OrderFinder;
use App\Sale\Payment\Domain\PaymentId;
use App\Sale\Payment\Domain\PaymentRepository;
use App\Sale\Payment\Domain\PaymentStatus;
use App\Sale\Payment\Domain\Provider\PaymentProvider;
use App\Sale\Payment\Domain\Services\PaymentFinder;
use App\Sale\Shared\Domain\UserId;

class PaymentUpdater
{
    public function __construct(
        private PaymentFinder $paymentFinder,
        private OrderFinder $orderFinder,
        private PaymentRepository $repository,
        private PaymentProvider $provider,
    ) {
    }

    public function __invoke(PaymentId $id, OrderId $orderId, UserId $userId, string $token): void
    {
        $order = $this->orderFinder->__invoke($orderId, $userId);
        $payment = $this->paymentFinder->__invoke($id, $order->id());

        $payment->changeStatus(PaymentStatus::processing());

        $this->repository->update($payment);
    }
}
