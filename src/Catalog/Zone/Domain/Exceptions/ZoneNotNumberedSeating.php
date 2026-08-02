<?php

namespace App\Catalog\Zone\Domain\Exceptions;

use Exception;
use Throwable;

class ZoneNotNumberedSeating extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('zone.zone_not_numbered_seating', 0, $previous);
    }
}