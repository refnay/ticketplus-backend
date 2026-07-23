<?php

namespace App\Shared\Domain\Exceptions;

use Exception;
use Throwable;

class ExpiredOrInvalidResetToken extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('common.expired_or_invalid_reset_token', 0, $previous);
    }
}