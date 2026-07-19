<?php

namespace App\Shared\Domain\Exceptions;

use Exception;
use Throwable;

class DivisionByZeroAttempted extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('common.division_by_zero_attempted', 0, $previous);
    }
}