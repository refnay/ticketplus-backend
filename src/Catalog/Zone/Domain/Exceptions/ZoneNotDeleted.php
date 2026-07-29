<?php

namespace App\Catalog\Zone\Domain\Exceptions;

use Exception;
use Throwable;

class ZoneNotDeleted extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('zone.zone_not_deleted', 0, $previous);
    }
}