<?php

namespace App\Account\Member\Domain;

use App\Account\Company\Domain\CompanyId;
use App\Account\User\Domain\UserId;

interface MemberRepository
{
    public function save(Member $member): void;

    public function update(Member $member): void;

    public function delete(Member $member): void;

    public function findById(MemberId $id): ?Member;

    public function findByUserAndCompany(UserId $userId, CompanyId $companyId): ?Member;

    public function searchByFilters(array $filters, string $orderBy, string $order, ?int $limit, ?int $offset): array;

    public function countByFilters(array $filters): int;
}
