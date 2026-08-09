<?php

namespace App\Account\CompanyMember\Domain;

enum CompanyMemberStatusList: int
{
    case ACTIVE = 0;
    case INACTIVE = 1;
}