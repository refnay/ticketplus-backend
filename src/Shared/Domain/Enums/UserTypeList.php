<?php

namespace App\Shared\Domain\Enums;

enum UserTypeList: int
{
    case SIMPLE = 0;
    case WORKER = 1;
}
