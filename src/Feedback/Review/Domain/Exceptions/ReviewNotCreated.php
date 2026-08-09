<?php

namespace App\Feedback\Review\Domain\Exceptions;

use Exception;
use Throwable;

class ReviewNotCreated extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('review.review_not_created', 0, $previous);
    }
}