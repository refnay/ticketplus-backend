<?php

namespace App\Sale\Payment\Application\Port\Gateway;

use App\Sale\Payment\Domain\Payment;

interface TransactionGateway
{
    public function charge(Payment $payment, string $token): TransactionResult;
}
