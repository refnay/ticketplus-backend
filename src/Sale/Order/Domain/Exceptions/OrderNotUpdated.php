<?php

namespace App\Sale\Order\Domain\Exceptions;

use Exception;
use Throwable;

class OrderNotUpdated extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('order.order_not_updated', 0, $previous);
    }
}