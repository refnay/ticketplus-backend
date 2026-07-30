<?php

namespace App\Account\CompanyMember\Domain;

enum CompanyMemberRoleList: string
{
    case OWNER = 0;
    case ASSOCIATE = 1;
}