<?php

namespace App\Sale\Payment\Domain\Provider;

use App\Sale\Payment\Domain\Payment;

interface PaymentProvider
{
    public function process(Payment $payment, string $token): PaymentProviderResponse;
}
