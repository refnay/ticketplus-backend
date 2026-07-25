<?php

namespace App\Account\User\Domain\Exceptions;

use Exception;
use Throwable;

class UserEmailAlreadyExists extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('user.user_email_already_exists', 0, $previous);
    }
}