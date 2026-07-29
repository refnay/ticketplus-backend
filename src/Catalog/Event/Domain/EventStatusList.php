<?php

namespace App\Catalog\Event\Domain;


enum EventStatusList: int
{
    case DRAFT = 0;
    case PUBLISHED = 1;
    case SOLD_OUT = 2;
    case PAUSED = 3;
    case CANCELLED = 4;
    case COMPLETED = 5;
}
