<?php

namespace App\Sale\Shared\Domain\Exceptions;

use Exception;
use Throwable;

class ZoneQuantityExceeded extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('zone.zone_quantity_exceeded', 0, $previous);
    }
}