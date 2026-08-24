<?php

namespace App\Sale\Reference\Seat\Domain\Exceptions;

use Exception;
use Throwable;

class SeatNotFound extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('seat.seat_not_found', 0, $previous);
    }
}
