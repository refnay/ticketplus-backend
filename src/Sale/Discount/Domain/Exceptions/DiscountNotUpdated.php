<?php

namespace App\Sale\Discount\Domain\Exceptions;

use Exception;
use Throwable;

class DiscountNotUpdated extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('discount.discount_not_updated', 0, $previous);
    }
}