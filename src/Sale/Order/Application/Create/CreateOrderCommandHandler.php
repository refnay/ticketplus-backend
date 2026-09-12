<?php

namespace App\Sale\Order\Application\Create;

use App\Shared\Application\Security\AuthorizationContext;
use App\Sale\Discount\Domain\DiscountId;
use App\Sale\Reference\EventDay\Domain\EventDayId;
use App\Sale\Reference\User\Domain\UserId;

class CreateOrderCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private OrderCreator $creator)
    {
    }

    public function __invoke(CreateOrderCommand $command): string
    {
        $userId = $this->authorization->userId();

        return $this->creator->__invoke(
            EventDayId::fromString($command->day()),
            DiscountId::fromNullable($command->discount()),
            UserId::fromString($userId),
            $command->items(),
        );
    }
}
