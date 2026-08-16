<?php

namespace App\Sale\Payment\Application\Provider;

use App\Sale\Payment\Domain\Exceptions\PaymentProviderNotFound;
use App\Sale\Payment\Domain\Provider\PaymentProvider;

final readonly class PaymentProviderResolver
{
    /** @param iterable<PaymentProvider> $providers */
    public function __construct(private iterable $providers)
    {
    }

    public function resolve(int $type): PaymentProvider
    {
        foreach ($this->providers as $provider) {
            if ($provider->type() === $type) {
                return $provider;
            }
        }

        throw new PaymentProviderNotFound();
    }
}
