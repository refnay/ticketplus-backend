<?php

namespace App\Account\Member\Domain\Exceptions;

use Exception;
use Throwable;

class MemberNotCreated extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('member.member_not_created', 0, $previous);
    }
}