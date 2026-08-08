<?php

namespace App\Account\Company\Domain\Exceptions;

use Exception;
use Throwable;

class CompanyLogoNotUploaded extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('company.company_logo_not_uploaded', 0, $previous);
    }
}