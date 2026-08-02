<?php

namespace App\Account\Company\Domain;

interface CompanyMemberRepository
{
    public function searchByFilters(array $filters, string $orderBy, string $order, ?int $limit, ?int $offset): array;

    public function countByFilters(array $filters): int;
}