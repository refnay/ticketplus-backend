<?php

namespace App\Shared\Domain\Exceptions;

use Exception;
use Throwable;

class ValueObjectUsedWhileNull extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('common.value_object_used_while_null', 0, $previous);
    }
}