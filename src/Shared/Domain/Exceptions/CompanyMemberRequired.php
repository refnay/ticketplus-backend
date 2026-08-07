<?php

namespace App\Shared\Domain\Exceptions;

use Exception;
use Throwable;

class CompanyMemberRequired extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('common.company_member_required', 0, $previous);
    }
}