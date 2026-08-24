<?php

namespace App\Sale\Payment\Application\Port\Payment;

use App\Sale\Payment\Domain\Payment;

interface PaymentProvider
{
    public function process(Payment $payment, string $token): PaymentProviderResponse;
}
