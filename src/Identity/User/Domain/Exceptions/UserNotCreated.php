<?php

namespace App\Identity\User\Domain\Exceptions;

use Exception;
use Throwable;

class UserNotCreated extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('user.user_not_created', 0, $previous);
    }
}