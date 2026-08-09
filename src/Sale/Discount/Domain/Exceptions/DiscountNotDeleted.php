<?php

namespace App\Sale\Discount\Domain\Exceptions;

use Exception;
use Throwable;

class DiscountNotDeleted extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('discount.discount_not_deleted', 0, $previous);
    }
}