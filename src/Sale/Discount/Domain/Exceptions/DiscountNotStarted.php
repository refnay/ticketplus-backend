<?php

namespace App\Sale\Discount\Domain\Exceptions;

use Exception;
use Throwable;

class DiscountNotStarted extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('discount.discount_not_started', 0, $previous);
    }
}