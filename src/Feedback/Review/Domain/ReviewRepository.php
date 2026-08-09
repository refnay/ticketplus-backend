<?php

namespace App\Feedback\Review\Domain;

use App\Feedback\Shared\Domain\UserId;

interface ReviewRepository
{
    public function save(Review $review): void;

    public function update(Review $review): void;

    public function delete(Review $review): void;

    public function findById(ReviewId $id, UserId $userId): ?Review;

    public function searchByFilters(array $filters, string $orderBy, string $order, ?int $limit, ?int $offset): array;

    public function countByFilters(array $filters): int;
}