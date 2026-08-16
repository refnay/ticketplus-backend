<?php

namespace App\Sale\Order\Domain\Services;

use App\Sale\Order\Domain\Exceptions\OrderNotFound;
use App\Sale\Order\Domain\Order;
use App\Sale\Order\Domain\OrderId;
use App\Sale\Order\Domain\OrderRepository;

class OrderForceFinder
{
    public function __construct(private OrderRepository $repository)
    {
    }

    public function __invoke(OrderId $id): Order
    {
        $order = $this->repository->find($id);

        if (is_null($order)) {
            throw new OrderNotFound();
        }

        return $order;
    }
}