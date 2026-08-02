<?php

namespace App\Shared\Domain\Exceptions;

use Exception;
use Throwable;

class UserNotAllowed extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('common.user_not_allowed', 0, $previous);
    }
}