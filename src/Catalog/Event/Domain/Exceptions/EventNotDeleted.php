<?php

namespace App\Catalog\Event\Domain\Exceptions;

use Exception;
use Throwable;

class EventNotDeleted extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('event.event_not_updated', 0, $previous);
    }
}