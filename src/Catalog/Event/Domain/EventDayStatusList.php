<?php

namespace App\Catalog\Event\Domain;


enum EventStatusList: int
{
    case SCHEDULED = 0;
    case IN_PROGRESS = 1;
    case COMPLETED = 2;
    case CANCELLED = 3;
    case POSTPONED = 4;
}
