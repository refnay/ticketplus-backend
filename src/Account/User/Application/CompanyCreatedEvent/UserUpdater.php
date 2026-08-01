<?php

namespace App\Account\User\Application\CompanyCreatedEvent;

use App\Account\Company\Domain\CompanyId;
use App\Account\User\Domain\Services\UserFinder;
use App\Account\User\Domain\UserCurrentCompany;
use App\Account\User\Domain\UserId;
use App\Account\User\Domain\UserRepository;
use App\Account\User\Domain\UserType;

class UserUpdater
{
    public function __construct(private UserRepository $repository, private UserFinder $finder)
    {
    }

    public function __invoke(UserId $userId, CompanyId $companyId): void
    {
        $user = $this->finder->__invoke($userId);

        $user->changeCurrentCompany(UserCurrentCompany::fromString($companyId->value()));
        $user->changeType(UserType::worker());

        $this->repository->update($user);
    }
}
