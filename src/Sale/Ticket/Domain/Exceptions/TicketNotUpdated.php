<?php

namespace App\Sale\Ticket\Domain\Exceptions;

use Exception;
use Throwable;

class TicketNotUpdated extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('ticket.ticket_not_updated', 0, $previous);
    }
}