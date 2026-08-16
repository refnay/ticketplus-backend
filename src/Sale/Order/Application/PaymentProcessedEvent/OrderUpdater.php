<?php

namespace App\Sale\Order\Application\PaymentProcessedEvent;

use App\Sale\Order\Domain\OrderId;
use App\Sale\Order\Domain\OrderRepository;
use App\Sale\Order\Domain\Services\OrderFinder;
use App\Sale\Payment\Domain\PaymentStatus;
use App\Sale\Shared\Domain\UserId;

class OrderUpdater
{
    public function __construct(private OrderRepository $repository, private OrderFinder $finder)
    {
    }

    public function __invoke(OrderId $id, UserId $userId, PaymentStatus $status): void
    {
        $order = $this->finder->__invoke($id, $userId);

        $this->repository->update($order);
    }
}
