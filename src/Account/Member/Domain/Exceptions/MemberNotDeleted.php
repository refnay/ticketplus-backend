<?php

namespace App\Account\Member\Domain\Exceptions;

use Exception;
use Throwable;

class MemberNotDeleted extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('member.member_not_deleted', 0, $previous);
    }
}