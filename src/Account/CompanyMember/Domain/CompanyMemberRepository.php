<?php

namespace App\Account\CompanyMember\Domain;

interface CompanyMemberRepository
{
    public function save(CompanyMember $companyMember): void;
    
    public function findById(CompanyMemberId $id): ?CompanyMember;

    public function searchByFilters(array $filters, string $orderBy, string $order, ?int $limit, ?int $offset): array;

    public function countByFilters(array $filters): int;
}