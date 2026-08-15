<?php

namespace App\Sale\Discount\Domain\Exceptions;

use Exception;
use Throwable;

class DiscountExpired extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('discount.discount_expired', 0, $previous);
    }
}