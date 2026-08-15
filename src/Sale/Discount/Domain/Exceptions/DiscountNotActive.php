<?php

namespace App\Sale\Discount\Domain\Exceptions;

use Exception;
use Throwable;

class DiscountNotActive extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('discount.discount_not_active', 0, $previous);
    }
}