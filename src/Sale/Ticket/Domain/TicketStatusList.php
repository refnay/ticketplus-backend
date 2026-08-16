<?php

namespace App\Sale\Ticket\Domain;

enum TicketStatusList: int
{
    case ACTIVE = 0;
    case USED = 1;
    case CANCELLED = 2;
    case REFUNDED = 3;
    case EXPIRED = 4;
}
