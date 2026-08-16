<?php

namespace App\Sale\Order\Domain\Exceptions;

use Exception;
use Throwable;

class OrderNotCancelable extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('order.order_not_cancelable', 0, $previous);
    }
}