<?php

namespace App\Sale\Payment\Application\Resolver;

use App\Sale\Payment\Domain\Exceptions\PaymentProviderNotFound;
use App\Sale\Payment\Application\Port\Payment\PaymentProvider;
use App\Sale\Payment\Domain\Provider\PaymentProviderList;

final readonly class PaymentProviderResolver
{
    public function __construct(private PaymentProvider $mercadoPago)
    {
    }

    public function __invoke(string $provider): PaymentProvider
    {
        return match($provider) {
            PaymentProviderList::MERCADO_PAGO->value => $this->mercadoPago,
            default => throw new PaymentProviderNotFound(),
        };
    }
}
