<?php

namespace App\Account\CompanyMember\Domain;

enum CompanyMemberRoleList: int
{
    case OWNER = 0;
    case ASSOCIATE = 1;
}