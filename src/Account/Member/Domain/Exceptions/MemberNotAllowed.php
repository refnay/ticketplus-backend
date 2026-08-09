<?php

namespace App\Account\Member\Domain\Exceptions;

use Exception;
use Throwable;

class MemberNotAllowed extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('member.member_not_allowed', 0, $previous);
    }
}