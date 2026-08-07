<?php

namespace App\Catalog\Event\Domain\Exceptions;

use Exception;
use Throwable;

class EventCoverImageNotUploaded extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('event.event_cover_image_not_uploaded', 0, $previous);
    }
}