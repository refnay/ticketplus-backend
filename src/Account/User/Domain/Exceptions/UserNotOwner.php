<?php

namespace App\Account\User\Domain\Exceptions;

use Exception;
use Throwable;

class UserNotOwner extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('user.user_not_owner', 0, $previous);
    }
}