<?php

namespace App\Account\User\Domain;

enum UserStatusList: int
{
    case PENDING = 0;
    case ACTIVE = 1;
    case INACTIVE = 2;
    case SUSPENDED = 3;

    public static function blocked(): array
    {
        return [self::INACTIVE->value, self::SUSPENDED->value];
    }
}
