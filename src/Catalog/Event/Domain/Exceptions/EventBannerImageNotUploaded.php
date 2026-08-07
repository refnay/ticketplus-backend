<?php

namespace App\Catalog\Event\Domain\Exceptions;

use Exception;
use Throwable;

class EventBannerImageNotUploaded extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('event.event_banner_image_not_uploaded', 0, $previous);
    }
}