<?php

namespace App\Catalog\Event\Domain;

use App\Catalog\Shared\Domain\CompanyId;

interface EventRepository
{
    public function save(Event $event): void;

    public function update(Event $event): void;

    public function delete(Event $event): void;

    public function findById(EventId $id, CompanyId $companyId): ?Event;

    public function findBySlug(EventSlug $slug, CompanyId $companyId): ?Event;

    public function searchByFilters(array $filters, string $orderBy, string $order, ?int $limit, ?int $offset): array;

    public function countByFilters(array $filters): int;
}