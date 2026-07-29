<?php

namespace App\Account\Company\Domain;

enum CompanyStatusList: int
{
    case PENDING = 0;
    case ACTIVE = 1;
    case INACTIVE = 2;
    case SUSPENDED = 3;
}