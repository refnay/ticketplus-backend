<?php

namespace App\Account\Company\Domain;

enum CompanyMemberStatusList: int
{
    case ACTIVE = 0;
    case INACTIVE = 1;
}