<?php

namespace App\Sale\Order\Application\Create;

use App\Sale\Discount\Domain\DiscountId;
use App\Sale\Shared\Domain\EventDayId;
use App\Sale\Shared\Domain\EventId;
use App\Sale\Shared\Domain\UserId;

class CreateOrderCommandHandler
{
    public function __construct(private OrderCreator $creator)
    {
    }

    public function __invoke(CreateOrderCommand $command): string
    {
        return $this->creator->__invoke(
            EventId::fromString($command->event()),
            EventDayId::fromString($command->day()),
            DiscountId::fromNullable($command->discount()),
            UserId::fromString($command->session()->user()),
            $command->zones(),
        );
    }
}
