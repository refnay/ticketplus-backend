<?php

namespace App\Sale\Payment\Application\Resolver;

use App\Sale\Payment\Application\Port\Gateway\TransactionGateway;
use App\Sale\Payment\Domain\Exceptions\TransactionGatewayNotFound;
use App\Sale\Payment\Domain\Gateway\TransactionGatewayList;

final readonly class TransactionGatewayResolver
{
    public function __construct(private TransactionGateway $mercadoPago)
    {
    }

    public function __invoke(string $gateway): TransactionGateway
    {
        return match($gateway) {
            TransactionGatewayList::MERCADO_PAGO->value => $this->mercadoPago,
            default => throw new TransactionGatewayNotFound(),
        };
    }
}
