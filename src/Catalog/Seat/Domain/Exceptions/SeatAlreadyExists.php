<?php

namespace App\Catalog\Seat\Domain\Exceptions;

use Exception;
use Throwable;

class SeatAlreadyExists extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('seat.seat_already_exists', 0, $previous);
    }
}