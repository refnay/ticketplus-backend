<?php

namespace App\Sale\Order\Application\Expire;

use App\Sale\Order\Domain\OrderId;
use App\Sale\Order\Domain\OrderRepository;
use App\Sale\Order\Domain\OrderStatus;
use App\Sale\Order\Domain\Services\OrderForceFinder;

class OrderExpirator
{
    public function __construct(private OrderForceFinder $finder, private OrderRepository $repository)
    {
    }

    public function __invoke(OrderId $id): void
    {
        $order = $this->finder->__invoke($id);

        if ($order->expiresAt()->expired()) {
            return;
        }
        
        $order->changeStatus(OrderStatus::expired());
        $this->repository->update($order);
    }
}
