<?php

namespace App\Sale\Order\Domain\Exceptions;

use Exception;
use Throwable;

class OrderNotFound extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('order.order_not_found', 0, $previous);
    }
}