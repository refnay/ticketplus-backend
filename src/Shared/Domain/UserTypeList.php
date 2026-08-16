<?php

namespace App\Shared\Domain;

enum UserTypeList: int
{
    case SIMPLE = 0;
    case WORKER = 1;
}
