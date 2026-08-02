<?php

namespace App\Account\Company\Domain\Exceptions;

use Exception;
use Throwable;

class CompanyNotCreated extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('company.company_not_created', 0, $previous);
    }
}