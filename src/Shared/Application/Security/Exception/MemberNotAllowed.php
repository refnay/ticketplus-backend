<?php

namespace App\Shared\Application\Security\Exception;

use Exception;
use Throwable;

class MemberNotAllowed extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('common.member_not_allowed', 0, $previous);
    }
}
