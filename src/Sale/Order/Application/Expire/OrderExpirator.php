<?php

namespace App\Sale\Order\Application\Expire;

use App\Sale\Order\Domain\OrderId;
use App\Sale\Order\Domain\OrderRepository;
use App\Sale\Order\Domain\OrderStatus;
use App\Sale\Order\Domain\Services\OrderForceFinder;
use App\Sale\Payment\Domain\Exceptions\PaymentNotFound;
use App\Sale\Payment\Domain\Services\PaymentByOrderFinder;

class OrderExpirator
{
    public function __construct(
        private OrderForceFinder $orderFinder,
        private PaymentByOrderFinder $paymentFinder,
        private OrderRepository $repository,

    ) {
    }

    public function __invoke(OrderId $id): void
    {
        $order = $this->orderFinder->__invoke($id);
        
        if ($order->status()->paid()) {
            return;
        }

        try {
            $payment = $this->paymentFinder->__invoke($order->id());
            if ($payment->status()->isProcessing()) {
                return;
            }
        } catch (PaymentNotFound) {
        }

        if ($order->expiresAt()->expired()) {
            return;
        }

        $order->changeStatus(OrderStatus::expired());
        $this->repository->update($order);
    }
}
