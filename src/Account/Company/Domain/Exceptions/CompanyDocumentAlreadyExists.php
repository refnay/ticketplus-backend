<?php

namespace App\Account\Company\Domain\Exceptions;

use Exception;
use Throwable;

class CompanyDocumentAlreadyExists extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('company.company_document_already_exists', 0, $previous);
    }
}