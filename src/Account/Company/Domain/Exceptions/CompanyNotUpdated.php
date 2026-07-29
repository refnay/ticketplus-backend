<?php

namespace App\Account\Company\Domain\Exceptions;

use Exception;
use Throwable;

class CompanyNotUpdated extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('company.company_not_updated', 0, $previous);
    }
}