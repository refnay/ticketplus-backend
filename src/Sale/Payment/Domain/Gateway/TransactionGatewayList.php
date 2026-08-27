<?php

namespace App\Sale\Payment\Domain\Gateway;

enum TransactionGatewayList: string
{
    case MERCADO_PAGO = 'MP';
}
