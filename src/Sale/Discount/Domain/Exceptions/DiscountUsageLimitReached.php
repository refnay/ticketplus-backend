<?php

namespace App\Sale\Discount\Domain\Exceptions;

use Exception;
use Throwable;

class DiscountUsageLimitReached extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('discount.discount_usage_limit_reached', 0, $previous);
    }
}