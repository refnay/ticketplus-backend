<?php

namespace App\Catalog\Event\Domain\Exceptions;

use Exception;
use Throwable;

class EventThumbnailNotUploaded extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('event.event_thumbnail_not_uploaded', 0, $previous);
    }
}
