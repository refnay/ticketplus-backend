<?php

namespace App\Account\User\Domain\Exceptions;

use Exception;
use Throwable;

class UserDocumentAlreadyExists extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('user.user_document_already_exists', 0, $previous);
    }
}