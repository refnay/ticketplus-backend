<?php

namespace App\Sale\Order\Application\PaymentApprovedEvent;

use App\Sale\Order\Domain\OrderId;
use App\Sale\Order\Domain\OrderRepository;
use App\Sale\Order\Domain\OrderStatus;
use App\Sale\Order\Domain\Services\OrderFinder;
use App\Sale\Reference\User\Domain\UserId;

class OrderUpdater
{
    public function __construct(private OrderRepository $repository, private OrderFinder $finder)
    {
    }

    public function __invoke(OrderId $id, UserId $userId): void
    {
        $order = $this->finder->__invoke($id, $userId);
        $order->changeStatus(OrderStatus::paid());

        $this->repository->update($order);
    }
}
