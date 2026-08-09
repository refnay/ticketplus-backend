<?php

namespace App\Account\CompanyMember\Application\CompanyCreatedEvent;

use App\Account\Company\Domain\CompanyId;
use App\Account\CompanyMember\Domain\CompanyMember;
use App\Account\CompanyMember\Domain\CompanyMemberRepository;
use App\Account\CompanyMember\Domain\CompanyMemberRole;
use App\Account\User\Domain\UserId;

class CompanyMemberCreator
{
    public function __construct(private CompanyMemberRepository $repository)
    {
    }

    public function __invoke(UserId $userId, CompanyId $companyId): void
    {
        $companyMember = CompanyMember::create(
            CompanyMemberRole::owner(),
            $userId,
            $companyId,
        );

        $this->repository->save($companyMember);
    }
}
