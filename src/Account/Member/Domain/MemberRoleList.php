<?php

namespace App\Account\Member\Domain;

enum MemberRoleList: int
{
    case OWNER = 0;
    case ASSOCIATE = 1;
}