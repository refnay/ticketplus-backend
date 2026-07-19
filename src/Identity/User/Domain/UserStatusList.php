<?php

namespace App\Identity\User\Domain;


enum UserStatusList: int
{
    case PENDING = 0;
    case ACTIVE = 1;
    case INACTIVE = 2;
    case SUSPENDED = 3;
}
