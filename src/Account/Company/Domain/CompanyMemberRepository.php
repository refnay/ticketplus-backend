<?php

namespace App\Account\Company\Domain;

use App\Account\User\Domain\UserId;

interface CompanyMemberRepository
{
    public function findByUserAndCompany(UserId $userId, CompanyId $companyId): ?CompanyMember;

    public function searchByFilters(array $filters, string $orderBy, string $order, ?int $limit, ?int $offset): array;

    public function countByFilters(array $filters): int;
}