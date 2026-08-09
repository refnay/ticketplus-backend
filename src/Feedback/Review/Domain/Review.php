<?php

namespace App\Feedback\Review\Domain;

use App\Feedback\Shared\Domain\EventId;
use App\Feedback\Shared\Domain\UserId;

class Review
{
    private ReviewId $id;
    private ReviewRating $rating;
    private ReviewComment $comment;
    private UserId $userId;
    private EventId $eventId;

    public function __construct(
        ReviewId $id,
        ReviewRating $rating,
        ReviewComment $comment,
        UserId $userId,
        EventId $eventId,
    ) {
        $this->id = $id;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->userId = $userId;
        $this->eventId = $eventId;
    }

    public static function create(ReviewRating $rating, ReviewComment $comment, UserId $userId, EventId $eventId): self
    {
        return new self(ReviewId::generate(), $rating, $comment, $userId, $eventId);
    }

    public function id(): ReviewId
    {
        return $this->id;
    }

    public function rating(): ReviewRating
    {
        return $this->rating;
    }

    public function comment(): ReviewComment
    {
        return $this->comment;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function eventId(): EventId
    {
        return $this->eventId;
    }

    public function changeRating(ReviewRating $rating): void 
    {
        $this->rating = $rating;
    }

    public function changeComment(ReviewComment $comment): void
    {
        $this->comment = $comment;
    }
}
