<?php

namespace App\Account\User\Domain;


enum UserTypeList: int
{
    case SIMPLE = 0;
    case WORKER = 1;
}
