<?php

namespace App\Sale\Payment\Application\Update;

use App\Shared\Application\Security\AuthorizationContext;
use App\Sale\Order\Domain\OrderId;
use App\Sale\Payment\Domain\PaymentId;
use App\Sale\User\Domain\UserId;

class UpdatePaymentCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private PaymentUpdater $processor)
    {
    }

    public function __invoke(UpdatePaymentCommand $command): void
    {
        $this->processor->__invoke(
            PaymentId::fromString($command->payment()),
            OrderId::fromString($command->order()),
            UserId::fromString($this->authorization->userId()),
            $command->token(),
        );
    }
}
