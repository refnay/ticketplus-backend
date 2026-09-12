<?php

namespace App\Shared\Application\Security\Exception;

use Exception;
use Throwable;

class AuthenticationRequired extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('common.authentication_required', 0, $previous);
    }
}
