<?php

namespace App\Account\Company\Domain\Exceptions;

use Exception;
use Throwable;

class CompanyMemberNotAllowed extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('company.company_member_not_allowed', 0, $previous);
    }
}