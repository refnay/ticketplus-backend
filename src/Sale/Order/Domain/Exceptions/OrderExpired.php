<?php

namespace App\Sale\Order\Domain\Exceptions;

use Exception;
use Throwable;

class OrderExpired extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('order.order_expired', 0, $previous);
    }
}