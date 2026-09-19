<?php

namespace App\Sale\Ticket\Domain\Exceptions;

use Exception;
use Throwable;

class TicketNotValidToday extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('ticket.ticket_not_valid_today', 0, $previous);
    }
}