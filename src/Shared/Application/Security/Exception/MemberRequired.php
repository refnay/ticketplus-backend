<?php

namespace App\Shared\Application\Security\Exception;

use Exception;
use Throwable;

class MemberRequired extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('common.member_required', 0, $previous);
    }
}
