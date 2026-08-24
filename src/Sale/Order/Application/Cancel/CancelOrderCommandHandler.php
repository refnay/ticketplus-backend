<?php

namespace App\Sale\Order\Application\Cancel;

use App\Shared\Application\Security\AuthorizationContext;
use App\Sale\Order\Domain\OrderId;
use App\Sale\Reference\User\Domain\UserId;

class CancelOrderCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private OrderCancelator $cancelator)
    {
    }

    public function __invoke(CancelOrderCommand $command): void
    {
        $this->authorization->requireAllPermissions();

        $this->cancelator->__invoke(
            OrderId::fromString($command->id()),
            UserId::fromString($this->authorization->userId()),
        );
    }
}
