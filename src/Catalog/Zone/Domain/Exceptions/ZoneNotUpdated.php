<?php

namespace App\Catalog\Zone\Domain\Exceptions;

use Exception;
use Throwable;

class ZoneNotUpdated extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('zone.zone_not_updated', 0, $previous);
    }
}