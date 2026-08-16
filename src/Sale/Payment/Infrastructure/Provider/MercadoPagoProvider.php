<?php

namespace App\Sale\Payment\Infrastructure\Provider;

use App\Sale\Payment\Domain\Payment;
use App\Sale\Payment\Domain\Provider\PaymentProvider;
use App\Sale\Payment\Domain\Provider\ProviderResponse;

final class MercadoPagoProvider implements PaymentProvider
{
    public function process(Payment $payment, string $token): ProviderResponse
    {
        return new ProviderResponse('', '', 0);
    }
}