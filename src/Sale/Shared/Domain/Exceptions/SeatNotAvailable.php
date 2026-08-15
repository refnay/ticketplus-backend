<?php

namespace App\Sale\Shared\Domain\Exceptions;

use Exception;
use Throwable;

class SeatNotAvailable extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('seat.seat_not_available', 0, $previous);
    }
}