<?php

namespace App\Catalog\Seat\Domain\Exceptions;

use Exception;
use Throwable;

class SeatNotDeleted extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('seat.seat_not_deleted', 0, $previous);
    }
}