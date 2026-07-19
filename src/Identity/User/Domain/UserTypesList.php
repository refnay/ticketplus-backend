<?php

namespace App\Identity\User\Domain;


enum UserTypesList: int
{
    case SIMPLE = 0;
    case WORKER = 1;
}
