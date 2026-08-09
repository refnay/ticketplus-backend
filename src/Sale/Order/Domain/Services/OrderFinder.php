<?php

namespace App\Sale\Order\Domain\Services;

use App\Sale\Order\Domain\Exceptions\OrderNotFound;
use App\Sale\Order\Domain\Order;
use App\Sale\Order\Domain\OrderId;
use App\Sale\Order\Domain\OrderRepository;
use App\Sale\Shared\Domain\UserId;

class OrderFinder
{
    public function __construct(private OrderRepository $repository)
    {
    }

    public function __invoke(OrderId $id, UserId $userId): Order
    {
        $order = $this->repository->findById($id, $userId);

        if (is_null($order)) {
            throw new OrderNotFound();
        }

        return $order;
    }
}