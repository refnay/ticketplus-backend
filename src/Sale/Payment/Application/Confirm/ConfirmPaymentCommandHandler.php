<?php

namespace App\Sale\Payment\Application\Confirm;

use App\Shared\Application\Security\AuthorizationContext;
use App\Sale\Payment\Domain\PaymentId;
use App\Sale\Reference\User\Domain\UserId;

class ConfirmPaymentCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private PaymentConfirmer $confirmer) {}

    public function __invoke(ConfirmPaymentCommand $command): void
    {
        $userId = $this->authorization->userId();

        $this->confirmer->__invoke(
            PaymentId::fromString($command->payment()),
            UserId::fromString($userId),
            $command->token(),
        );
    }
}
