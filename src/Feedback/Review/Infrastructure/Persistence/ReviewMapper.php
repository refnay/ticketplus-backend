<?php

namespace App\Feedback\Review\Infrastructure\Persistence;

use App\Feedback\Review\Domain\Review;
use App\Feedback\Review\Domain\ReviewComment;
use App\Feedback\Review\Domain\ReviewId;
use App\Feedback\Review\Domain\ReviewRating;
use App\Feedback\Shared\Domain\EventId;
use App\Feedback\Shared\Domain\UserId;
use App\Shared\Infrastructure\Persistence\Entity\Review as ReviewEntity;

class ReviewMapper
{
    public function __construct(private RelationFetcher $fetcher)
    {
    }

    public function newEntity(Review $review): ReviewEntity
    {
        $entity = new ReviewEntity();

        $entity->setId($review->id()->toUuid());
        $entity->setRating($review->rating()->value());
        $entity->setComment($review->comment()->value());
        $entity->setAttendee($this->fetcher->user($review->userId()));
        $entity->setEvent($this->fetcher->event($review->eventId()));

        return $entity;
    }

    public function newDomain(ReviewEntity $entity): Review
    {
        $review = new Review(
            ReviewId::fromString($entity->getId()),
            ReviewRating::fromInt($entity->getRating()),
            ReviewComment::fromString($entity->getComment()),
            UserId::fromString($entity->getAttendee()->getId()),
            EventId::fromString($entity->getEvent()->getId()),
        );

        return $review;
    }

    public function update(ReviewEntity $entity, Review $review): void
    {
        $entity->setRating($review->rating()->value());
        $entity->setComment($review->comment()->value());
    }

    public function entityClass(): string
    {
        return ReviewEntity::class;
    }
}