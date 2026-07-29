<?php

namespace App\Catalog\Event\Domain\Exceptions;

use Exception;
use Throwable;

class EventDayNotFound extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('event.event_day_not_found', 0, $previous);
    }
}