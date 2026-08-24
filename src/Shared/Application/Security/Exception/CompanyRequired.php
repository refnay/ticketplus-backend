<?php

namespace App\Shared\Application\Security\Exception;

use Exception;
use Throwable;

class CompanyRequired extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('common.company_required', 0, $previous);
    }
}
