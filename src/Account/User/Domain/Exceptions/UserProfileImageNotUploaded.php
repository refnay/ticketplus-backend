<?php

namespace App\Account\User\Domain\Exceptions;

use Exception;
use Throwable;

class UserProfileImageNotUploaded extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('user.profile_image_not_uploaded', 0, $previous);
    }
}