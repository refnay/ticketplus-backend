<?php

namespace App\Sale\Payment\Domain\Exceptions;

use Exception;
use Throwable;

class PaymentMethodNotSupported extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('payment.payment_method_not_supported', 0, $previous);
    }
}