<?php

namespace App\Sale\Payment\Application\Update;

use App\Sale\Order\Domain\OrderId;
use App\Sale\Payment\Domain\PaymentId;
use App\Sale\Payment\Domain\PaymentToken;
use App\Sale\Shared\Domain\UserId;

class UpdatePaymentCommandHandler
{
    public function __construct(private PaymentUpdater $updater)
    {
    }

    public function __invoke(UpdatePaymentCommand $command): void
    {
        $this->updater->__invoke(
            PaymentId::fromString($command->payment()),
            PaymentToken::fromString($command->token()),
            OrderId::fromString($command->order()),
            UserId::fromString($command->session()->user()),
        );
    }
}