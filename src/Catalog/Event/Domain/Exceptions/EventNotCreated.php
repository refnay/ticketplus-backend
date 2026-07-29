<?php

namespace App\Catalog\Event\Domain\Exceptions;

use Exception;
use Throwable;

class EventNotCreated extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('event.event_not_created', 0, $previous);
    }
}