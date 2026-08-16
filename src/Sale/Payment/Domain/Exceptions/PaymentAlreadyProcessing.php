<?php

namespace App\Sale\Payment\Domain\Exceptions;

use Exception;
use Throwable;

class PaymentAlreadyProcessing extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('payment.payment_alreaady_processing', 0, $previous);
    }
}