<?php

namespace App\Account\Member\Domain\Exceptions;

use Exception;
use Throwable;

class MemberNotUpdated extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('member.member_not_updated', 0, $previous);
    }
}