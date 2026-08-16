<?php

namespace App\Sale\Payment\Application\Provider;

use App\Sale\Payment\Domain\Exceptions\PaymentProviderNotFound;
use App\Sale\Payment\Domain\Provider\PaymentProvider;
use App\Sale\Payment\Domain\Provider\ProviderList;

final readonly class PaymentProviderResolver
{
    public function __construct(private PaymentProvider $mercadoPago)
    {
    }

    public function resolve(int $provider): PaymentProvider
    {
        return match($provider) {
            ProviderList::MERCADO_PAGO => $this->mercadoPago,
            default => throw new PaymentProviderNotFound(),
        };
    }
}
