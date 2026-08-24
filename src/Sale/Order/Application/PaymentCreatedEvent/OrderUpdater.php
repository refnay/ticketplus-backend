<?php

namespace App\Sale\Order\Application\PaymentCreatedEvent;

use App\Sale\Order\Domain\OrderId;
use App\Sale\Order\Domain\OrderPaymentMethod;
use App\Sale\Order\Domain\OrderRepository;
use App\Sale\Order\Domain\Services\OrderFinder;
use App\Sale\User\Domain\UserId;

class OrderUpdater
{
    public function __construct(private OrderRepository $repository, private OrderFinder $finder)
    {
    }

    public function __invoke(OrderId $id, OrderPaymentMethod $paymentMethod, UserId $userId): void
    {
        $order = $this->finder->__invoke($id, $userId);
        $order->changePaymentMethod($paymentMethod);

        $this->repository->update($order);
    }
}
