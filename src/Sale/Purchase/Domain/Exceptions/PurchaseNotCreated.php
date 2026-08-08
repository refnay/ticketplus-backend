<?php

namespace App\Sale\Purchase\Domain\Exceptions;

use Exception;
use Throwable;

class PurchaseNotCreated extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('purchase.purchase_not_created', 0, $previous);
    }
}