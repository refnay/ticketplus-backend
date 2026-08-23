<?php

namespace App\Sale\Payment\Infrastructure\Provider;

use App\Sale\Payment\Domain\Payment;
use App\Sale\Payment\Domain\PaymentMethodList;
use App\Sale\Payment\Domain\PaymentStatusList;
use App\Sale\Payment\Domain\Provider\PaymentProvider;
use App\Sale\Payment\Domain\Provider\PaymentProviderList;
use App\Sale\Payment\Domain\Provider\PaymentProviderResponse;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;

final class MercadoPagoPaymentProvider implements PaymentProvider
{
    private PaymentClient $client;

    public function __construct(string $accessToken)
    {
        MercadoPagoConfig::setAccessToken($accessToken);
        $this->client = new PaymentClient();
    }

    public function process(Payment $payment, string $token): PaymentProviderResponse
    {
        /*
        $response = $this->client->create([
            'transaction_amount' => $payment->amount()->value(),
            'token' => $token,
            'payment_method_id' => PaymentMethodList::from($payment->method()->value())->toMercadoPago(),
            'payer' => [
                'email' => $payment->payer()->email(),
            ],
        ]);

        return new PaymentProviderResponse(
            $response->id,
            PaymentProviderList::MERCADO_PAGO->value,
            PaymentStatusList::fromMercadoPago($response->status)->value,
        );
        */

        return new PaymentProviderResponse(
            'TESTTESTTESTTEST',
            PaymentProviderList::MERCADO_PAGO->value,
            2,
        );
    }
}