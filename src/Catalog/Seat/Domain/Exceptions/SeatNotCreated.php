<?php

namespace App\Catalog\Seat\Domain\Exceptions;

use Exception;
use Throwable;

class SeatNotCreated extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('seat.seat_not_created', 0, $previous);
    }
}