<?php

namespace App\Sale\Order\Application\Create;

use App\Shared\Application\Security\AuthorizationContext;
use App\Sale\Discount\Domain\DiscountId;
use App\Sale\EventDay\Domain\EventDayId;
use App\Sale\Event\Domain\EventId;
use App\Sale\User\Domain\UserId;

class CreateOrderCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private OrderCreator $creator)
    {
    }

    public function __invoke(CreateOrderCommand $command): string
    {
        $this->authorization->requireAllPermissions();

        return $this->creator->__invoke(
            EventId::fromString($command->event()),
            EventDayId::fromString($command->day()),
            DiscountId::fromNullable($command->discount()),
            UserId::fromString($this->authorization->userId()),
            $command->items(),
        );
    }
}
