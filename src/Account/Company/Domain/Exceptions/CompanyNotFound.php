<?php

namespace App\Account\Company\Domain\Exceptions;

use Exception;
use Throwable;

class CompanyNotFound extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('company.company_not_found', 0, $previous);
    }
}