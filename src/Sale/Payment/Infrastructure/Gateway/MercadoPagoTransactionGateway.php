<?php

namespace App\Sale\Payment\Infrastructure\Gateway;

use App\Sale\Payment\Application\Port\Gateway\TransactionGateway;
use App\Sale\Payment\Application\Port\Gateway\TransactionResult;
use App\Sale\Payment\Domain\Gateway\TransactionGatewayList;
use App\Sale\Payment\Domain\Payment;
use App\Sale\Payment\Domain\PaymentMethodList;
use App\Sale\Payment\Domain\PaymentStatusList;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;

final class MercadoPagoTransactionGateway implements TransactionGateway
{
    private PaymentClient $client;

    public function __construct(string $accessToken)
    {
        MercadoPagoConfig::setAccessToken($accessToken);
        $this->client = new PaymentClient();
    }

    public function charge(Payment $payment, string $token): TransactionResult
    {
        $response = $this->client->create([
            'transaction_amount' => $payment->amount()->value(),
            'token' => $token,
            'payment_method_id' => PaymentMethodList::from($payment->method()->value())->toMercadoPago(),
            'payer' => [
                'email' => $payment->payer()->email(),
            ],
        ]);

        if ($response->id === null) {
            throw new \UnexpectedValueException('Mercado Pago did not return a payment identifier.');
        }

        return new TransactionResult(
            (string) $response->id,
            TransactionGatewayList::MERCADO_PAGO->value,
            PaymentStatusList::fromMercadoPago($response->status)->value,
        );
    }
}
