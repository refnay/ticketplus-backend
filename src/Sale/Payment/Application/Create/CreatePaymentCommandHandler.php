<?php

namespace App\Sale\Payment\Application\Create;

use App\Sale\Order\Domain\OrderId;
use App\Sale\Payment\Domain\PaymentMethod;
use App\Sale\Payment\Domain\PaymentPayer;
use App\Sale\User\Domain\UserId;

class CreatePaymentCommandHandler
{
    public function __construct(private PaymentCreator $creator)
    {
    }

    public function __invoke(CreatePaymentCommand $command): string
    {
        return $this->creator->__invoke(
            OrderId::fromString($command->order()),
            PaymentMethod::fromInt($command->method()),
            PaymentPayer::fromArray($command->payer()),
            UserId::fromString($command->session()->user()),
        );
    }
}
