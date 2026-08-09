<?php

namespace App\Account\Member\Domain;

enum MemberStatusList: int
{
    case ACTIVE = 0;
    case INACTIVE = 1;
}