<?php

namespace App\Identity\User\Domain\Exceptions;

use Exception;
use Throwable;

class UserPasswordIncorrect extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('user.password_incorrect', 0, $previous);
    }
}