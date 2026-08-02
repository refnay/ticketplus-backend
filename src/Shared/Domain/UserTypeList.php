<?php

namespace App\Shared\Domain;


enum UserTypesList: int
{
    case SIMPLE = 0;
    case WORKER = 1;
}
