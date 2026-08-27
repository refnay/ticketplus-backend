<?php

namespace App\Sale\Payment\Domain\Exceptions;

use Exception;
use Throwable;

class TransactionGatewayNotFound extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('payment.transaction_gateway_not_found', 0, $previous);
    }
}
