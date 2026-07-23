<?php

namespace App\Identity\User\Domain\Exceptions;

use Exception;
use Throwable;

class UserNotFound extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('user.user_not_found', 0, $previous);
    }
}