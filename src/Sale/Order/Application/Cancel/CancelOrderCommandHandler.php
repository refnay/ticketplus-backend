<?php

namespace App\Sale\Order\Application\Cancel;

use App\Sale\Order\Domain\OrderId;
use App\Sale\User\Domain\UserId;

class CancelOrderCommandHandler
{
    public function __construct(private OrderCancelator $cancelator)
    {
    }

    public function __invoke(CancelOrderCommand $command): void
    {
        $this->cancelator->__invoke(
            OrderId::fromString($command->id()),
            UserId::fromString($command->session()->user()),
        );
    }
}
