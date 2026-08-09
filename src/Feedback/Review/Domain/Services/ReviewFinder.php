<?php

namespace App\Feedback\Review\Domain\Services;

use App\Feedback\Review\Domain\Exceptions\ReviewNotFound;
use App\Feedback\Review\Domain\Review;
use App\Feedback\Review\Domain\ReviewId;
use App\Feedback\Review\Domain\ReviewRepository;
use App\Feedback\Shared\Domain\UserId;

class ReviewFinder
{
    public function __construct(private ReviewRepository $repository)
    {
    }

    public function __invoke(ReviewId $id, UserId $userId): Review
    {
        $review = $this->repository->findById($id, $userId);

        if (is_null($review)) {
            throw new ReviewNotFound();
        }

        return $review;
    }
}