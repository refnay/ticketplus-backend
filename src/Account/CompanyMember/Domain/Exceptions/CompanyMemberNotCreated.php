<?php

namespace App\Account\CompanyMember\Domain\Exceptions;

use Exception;
use Throwable;

class CompanyMemberNotCreated extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('company.company_member_not_created', 0, $previous);
    }
}