<?php

namespace App\Sale\Order\Application\Create;

use App\Sale\Shared\Domain\EventDayId;
use App\Sale\Shared\Domain\EventId;
use App\Sale\Shared\Domain\ZoneId;

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
            ZoneId::fromString($command->zone()),
            $command->quantity(),
            $command->seats(),
        );
    }
}