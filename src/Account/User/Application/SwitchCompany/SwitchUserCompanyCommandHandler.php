<?php

namespace App\Account\User\Application\SwitchCompany;

use App\Shared\Application\Security\AuthorizationContext;
use App\Account\User\Domain\UserCurrentCompany;
use App\Account\User\Domain\UserId;

class SwitchUserCompanyCommandHandler
{
    public function __construct(private AuthorizationContext $authorization, private UserCompanySwitcher $switcher)
    {
    }

    public function __invoke(SwitchUserCompanyCommand $command): void
    {
        $userId = $this->authorization->userId();

        $this->switcher->__invoke(
            UserId::fromString($userId),
            UserCurrentCompany::fromString($command->company()),
        );
    }
}