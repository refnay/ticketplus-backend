<?php

namespace App\Sale\Purchase\Domain\Exceptions;

use Exception;
use Throwable;

class PurchaseNotDeleted extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('purchase.purchase_not_deleted', 0, $previous);
    }
}