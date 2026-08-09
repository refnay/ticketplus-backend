<?php

namespace App\Sale\Ticket\Domain\Exceptions;

use Exception;
use Throwable;

class TicketNotFound extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('ticket.ticket_not_found', 0, $previous);
    }
}