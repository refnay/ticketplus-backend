<?php

namespace App\Catalog\Event\Domain\Exceptions;

use Exception;
use Throwable;

class EventAlreadyExists extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('event.event_already_exists', 0, $previous);
    }
}