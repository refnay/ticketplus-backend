<?php

namespace App\Feedback\Review\Domain\Exceptions;

use Exception;
use Throwable;

class ReviewNotUpdated extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('review.review_not_updated', 0, $previous);
    }
}