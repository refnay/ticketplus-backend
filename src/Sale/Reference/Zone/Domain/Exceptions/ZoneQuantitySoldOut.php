<?php

namespace App\Sale\Reference\Zone\Domain\Exceptions;

use Exception;
use Throwable;

class ZoneQuantitySoldOut extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('zone.zone_quantity_sold_out', 0, $previous);
    }
}
