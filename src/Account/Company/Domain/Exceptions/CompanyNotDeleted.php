<?php

namespace App\Account\Company\Domain\Exceptions;

use Exception;
use Throwable;

class CompanyNotDeleted extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('company.company_not_deleted', 0, $previous);
    }
}