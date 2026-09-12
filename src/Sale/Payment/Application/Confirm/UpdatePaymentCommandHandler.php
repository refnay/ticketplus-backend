<?php

namespace App\Sale\Payment\Application\Confirm;

use App\Shared\Application\Security\AuthorizationContext;
use App\Sale\Payment\Domain\PaymentId;
use App\Sale\Reference\User\Domain\UserId;

class UpdatePaymentCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private PaymentUpdater $processor)
    {
    }

    public function __invoke(UpdatePaymentCommand $command): void
    {
        $userId = $this->authorization->userId();

        $this->processor->__invoke(
            PaymentId::fromString($command->payment()),
            UserId::fromString($userId),
            $command->token(),
        );
    }
}
