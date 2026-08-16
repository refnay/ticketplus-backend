<?php

namespace App\Sale\Order\Domain\Exceptions;

use Exception;
use Throwable;

class OrderStatusNotAllowed extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('order.order_status_not_allowed', 0, $previous);
    }
}