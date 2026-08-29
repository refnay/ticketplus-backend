<?php

namespace App\Catalog\Event\Domain\Exceptions;

use Exception;
use Throwable;

class EventLogoNotUploaded extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('event.event_logo_not_uploaded', 0, $previous);
    }
}
