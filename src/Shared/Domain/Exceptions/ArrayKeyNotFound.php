<?php

namespace App\Shared\Domain\Exceptions;

use Exception;
use Throwable;

class ArrayKeyNotFound extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('common.array_key_not_found', 0, $previous);
    }
}