<?php

namespace App\Catalog\Shared\Domain\Exceptions;

use Exception;
use Throwable;

class OrderStatusNotFound extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('order.order_status_not_found', 0, $previous);
    }
}