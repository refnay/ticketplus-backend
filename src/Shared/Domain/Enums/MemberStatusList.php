<?php

namespace App\Shared\Domain\Enums;

enum MemberStatusList: int
{
    case ACTIVE = 0;
    case INACTIVE = 1;
}