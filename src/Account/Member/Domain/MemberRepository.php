<?php

namespace App\Account\Member\Domain;

interface MemberRepository
{
    public function save(Member $member): void;

    public function update(Member $member): void;

    public function delete(Member $member): void;
    
    public function findById(MemberId $id): ?Member;

    public function searchByFilters(array $filters, string $orderBy, string $order, ?int $limit, ?int $offset): array;

    public function countByFilters(array $filters): int;
}