<?php

namespace App\Feedback\Review\Domain\Exceptions;

use Exception;
use Throwable;

class ReviewNotFound extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('review.review_not_found', 0, $previous);
    }
}