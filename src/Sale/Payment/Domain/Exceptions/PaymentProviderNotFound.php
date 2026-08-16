<?php

namespace App\Sale\Payment\Domain\Exceptions;

use Exception;
use Throwable;

class PaymentProviderNotFound extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('payment.payment_provider_not_found', 0, $previous);
    }
}