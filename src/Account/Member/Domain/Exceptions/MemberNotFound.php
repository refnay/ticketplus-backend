<?php

namespace App\Account\Member\Domain\Exceptions;

use Exception;
use Throwable;

class MemberNotFound extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('member.member_not_found', 0, $previous);
    }
}