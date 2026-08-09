<?php

namespace App\Sale\Payment\Domain\Exceptions;

use Exception;
use Throwable;

class PaymentNotDeleted extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('payment.payment_not_deleted', 0, $previous);
    }
}