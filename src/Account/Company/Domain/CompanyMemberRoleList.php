<?php

namespace App\Account\Company\Domain;

enum CompanyMemberRoleList: int
{
    case OWNER = 0;
    case ASSOCIATE = 1;
}