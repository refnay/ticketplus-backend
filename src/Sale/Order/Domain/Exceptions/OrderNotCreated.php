<?php

namespace App\Sale\Order\Domain\Exceptions;

use Exception;
use Throwable;

class OrderNotCreated extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('order.order_not_created', 0, $previous);
    }
}