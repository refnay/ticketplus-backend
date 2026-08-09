<?php

namespace App\Sale\Order\Domain\Exceptions;

use Exception;
use Throwable;

class OrderNotDeleted extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('order.order_not_deleted', 0, $previous);
    }
}