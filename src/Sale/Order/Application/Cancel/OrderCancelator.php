<?php

namespace App\Sale\Order\Application\Cancel;

use App\Sale\Order\Domain\Exceptions\OrderNotCancelable;
use App\Sale\Order\Domain\OrderId;
use App\Sale\Order\Domain\OrderRepository;
use App\Sale\Order\Domain\OrderStatus;
use App\Sale\Order\Domain\Services\OrderFinder;
use App\Sale\Payment\Domain\Exceptions\PaymentNotFound;
use App\Sale\Payment\Domain\Services\PaymentByOrderFinder;
use App\Sale\Shared\Domain\UserId;

class OrderCancelator
{
    public function __construct(
        private OrderFinder $orderFinder,
        private PaymentByOrderFinder $paymentFinder,
        private OrderRepository $repository,
    ) {
    }

    public function __invoke(OrderId $id, UserId $userId): void
    {
        $order = $this->orderFinder->__invoke($id, $userId);
        
        if (!$order->status()->isPending()) {
            throw new OrderNotCancelable();
        }

        if ($order->expiresAt()->expired()) {
            throw new OrderNotCancelable();
        }

        try {
            $payment = $this->paymentFinder->__invoke($id);
            if ($payment->status()->isProcessing()) {
                throw new OrderNotCancelable();
            }
        } catch (PaymentNotFound) {
        }

        $order->changeStatus(OrderStatus::cancelled());
        
        $this->repository->update($order);
    }
}
