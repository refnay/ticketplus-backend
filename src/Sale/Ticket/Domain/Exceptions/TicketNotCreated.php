<?php

namespace App\Sale\Ticket\Domain\Exceptions;

use Exception;
use Throwable;

class TicketNotCreated extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('ticket.ticket_not_created', 0, $previous);
    }
}